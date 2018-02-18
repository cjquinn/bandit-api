<?php

namespace App\Model\Table;

use App\Model\Entity\Player;
use App\Model\Entity\Match;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PlayersTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Clubs',
                'Users'
            ]
        ]);

        $this->addBehavior('Timestamp');
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
        $validator
            ->requirePresence('user')
            ->notEmpty('user')
            ->addNested('user', $this->Users->getValidator('invite'));

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * @return void
     */
    public function patchEntityAdd(Player $player, array $data, $clubId)
    {
        $player->set('club_id', $clubId);

        $this->patchEntity($player, $data, [
            'fieldList' => ['user'],
            'validate' => 'add'
        ]);

        if ($player->getErrors()) {
            return;
        }

        $user = $this->Users
            ->findByEmail($player->user->email)
            ->first();

        if (!$user) {
            return;
        }

        if ($this->Clubs->hasMember($player->club_id, $user->id)) {
            $player->user->setError('email', [
                'duplicate' => 'A member of this club already exists with that email'
            ]);

            return;
        }

        $player->set('user_id', $user->id);
        $player->unsetProperty('user');
    }

    /**
     * @return void
     */
    public function patchEntityStats(Player $player, array $data)
    {
        $this->patchEntity($player, $data, [
            'fieldList' => [
                'rating',
                'losses',
                'wins'
            ],
            'validate' => false
        ]);
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, Player $player)
    {
        if ($player->isNew()) {
            $player->set('rating', Configure::read('Bandit.initialRating'));
        }
    }

    /**
     * @return array
     * @see https://en.wikipedia.org/wiki/Elo_rating_system#Mathematical_details
     */
    public function expectedScores($ratingA, $ratingB)
    {
        $a = 1 / (1 + pow(10, ($ratingB - $ratingA) / 400));
        $b = 1 - $a;

        return [
            'a' => $a,
            'b' => $b
        ];
    }

    /**
     * @return int
     * @see https://en.wikipedia.org/wiki/Elo_rating_system#Mathematical_details
     */
    public function ratingChange($expectedScore, $score, $kFactor)
    {
        return round($kFactor * ($score - $expectedScore));
    }

    /**
     * @param \App\Model\Entity\Matches $match
     * @param string $player - The letter of the player in the match (player_a|player_b)
     * @return bool
     */
    public function revert(Match $match, $player)
    {
        $this->connection()->transactional(function () use ($match, $player) {
            $snapshot = $match->{$player . '_snapshot'};
            $wins = $match->{$player . '_score'};
            $losses = $player === 'player_a'
                ? $match->player_b_score
                : $match->player_a_score;

            $player = $this->get($match->{$player . '_id'});

            $this->patchEntityStats($player, [
                'rating' => $snapshot['rating'] - $snapshot['difference'],
                'wins' => $snapshot['wins'] - $wins,
                'losses' => $snapshot['losses'] - $losses
            ]);

            $this->save($player);

            // Removed reputation gained from deleted match
            if ($match->deleted) {
                $this->Users->updateReputation($player->user_id, -1);
            }
        });

        return true;
    }

    /**
     * @return array
     */
    public function snapshot(Player $player, $expectedScore, $wins, $losses)
    {
        $winsRatingChange = $this->ratingChange($expectedScore, 1, $player->k_factor) * $wins;
        $lossesRatingChange = $this->ratingChange($expectedScore, 0, $player->k_factor) * $losses;

        $this->patchEntityStats($player, [
            'rating' => $player->rating + $winsRatingChange + $lossesRatingChange,
            'wins' => $player->wins + $wins,
            'losses' => $player->losses + $losses
        ]);

        $snapshot = [
            'rating' => $player->rating,
            'difference' => $player->rating - $player->getOriginal('rating'),
            'wins' => $player->wins,
            'losses' => $player->losses
        ];

        $this->save($player);

        return $snapshot;
    }

    /**
     * @return array
     */
    public function snapshots(Match $match)
    {
        $playerASnapShot = [];
        $playerBSnapShot = [];

        $this->connection()->transactional(function () use ($match, &$playerASnapShot, &$playerBSnapShot) {
            $playerA = $this->get($match->player_a_id);
            $playerB = $this->get($match->player_b_id);

            $date = $match->created
                ? $match->created->i18nFormat('Y-M-d')
                : Time::now()->i18nFormat('Y-M-d');

            // Get daily player rating
            $playerADailySnapshot = $this->Clubs->dailySnapshot(
                $match->club_id,
                $playerA->id,
                $date
            );
            $playerBDailySnapshot = $this->Clubs->dailySnapshot(
                $match->club_id,
                $playerB->id,
                $date
            );

            // Expected scores from daily rating
            $expectedScores = $this->expectedScores(
                $playerADailySnapshot['rating'],
                $playerBDailySnapshot['rating']
            );

            // Update player stats
            $playerASnapShot = $this->snapshot(
                $playerA,
                $expectedScores['a'],
                $match->player_a_score,
                $match->player_b_score
            );
            $playerBSnapShot = $this->snapshot(
                $playerB,
                $expectedScores['b'],
                $match->player_b_score,
                $match->player_a_score
            );

            if ($match->isNew()) {
                // Update reputation for new matches
                $this->Users->updateReputation($playerA->user_id, 1);
                $this->Users->updateReputation($playerB->user_id, 1);
            }
        });

        return [
            'a' => $playerASnapShot,
            'b' => $playerBSnapShot
        ];
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findOrdered(Query $query, array $options)
    {
        switch ($options['orderBy']) {
            case 'a-z':
                $query
                    ->leftJoinWith('Users')
                    ->orderAsc('Users.name');
                break;

            case 'games':
                $query
                    ->select(['total_matches' => sprintf(
                        '%s + %s',
                        $this->aliasField('wins'),
                        $this->aliasField('losses')
                    )])
                    ->orderDesc('total_matches')
                    ->enableAutoFields(true);
                break;

            case 'rating':
                $query->orderDesc('rating');
                break;
        }

        return $query;
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findPopulated(Query $query, array $options)
    {
        $query
            ->contain(['Users'])
            ->innerJoinWith('Users', function ($q) {
                $q->find('auth');

                return $q;
            });

        return $query;
    }

    /**
     * @return bool
     */
    public function isOwnedBy($id, $clubId)
    {
        return $this->exists([
            'id' => $id,
            'club_id' => $clubId
        ]);
    }
}
