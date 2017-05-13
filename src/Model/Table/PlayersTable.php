<?php

namespace App\Model\Table;

use App\Model\Entity\Player;
use App\Model\Entity\Result;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\Time;
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
     * @return void
     */
    public function patchEntityAdd(Player $player, array $data)
    {
        $this->patchEntity($player, $data, [
            'fieldList' => ['user'],
            'validate' => 'add'
        ]);

        if (!$player->errors()) {
            $user = $this->Users
                ->findByEmail($data['user']['email'])
                ->first();

            if ($user) {
                if ($this->Clubs->hasMember($player->club_id, $user->id)) {
                    $player->user->errors('email', [
                        'duplicate' => 'A member of this club already exists with that email'
                    ]);
                } else {
                    $player->set('user_id', $user->id);
                    $player->unsetProperty('user');
                }
            }
        }
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
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
        $validator
            ->requirePresence('user')
            ->notEmpty('user');

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
     * @param \App\Model\Entity\Results $result
     * @param string $player - The letter of the player in the result (player_a|player_b)
     * @return bool
     */
    public function revert(Result $result, $player)
    {
        $this->connection()->transactional(function () use ($result, $player) {
            $snapshot = $result->{$player . '_snapshot'};
            $wins = $result->{$player . '_score'};
            $losses = $player === 'player_a'
                ? $result->player_b_score
                : $result->player_a_score;

            $player = $this->get($result->{$player . '_id'});

            $this->patchEntityStats($player, [
                'rating' => $snapshot['rating'] - $snapshot['difference'],
                'wins' => $snapshot['wins'] - $wins,
                'losses' => $snapshot['losses'] - $losses
            ]);

            $this->save($player);

            if ($result->is_deleted) {
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
    public function snapshots(Result $result)
    {
        $playerASnapShot = [];
        $playerBSnapShot = [];

        $this->connection()->transactional(function () use ($result, &$playerASnapShot, &$playerBSnapShot) {
            $playerA = $this->get($result->player_a_id);
            $playerB = $this->get($result->player_b_id);

            $date = $result->created
                ? $result->created->i18nFormat('Y-M-d')
                : Time::now()->i18nFormat('Y-M-d');

            // Get daily player rating
            $playerADailySnapshot = $this->Clubs->dailySnapshot(
                $result->club_id,
                $playerA->id,
                $date
            );
            $playerBDailySnapshot = $this->Clubs->dailySnapshot(
                $result->club_id,
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
                $result->player_a_score,
                $result->player_b_score
            );
            $playerBSnapShot = $this->snapshot(
                $playerB,
                $expectedScores['b'],
                $result->player_b_score,
                $result->player_a_score
            );

            if ($result->isNew()) {
                // Update reputation for new results
                $this->Users->updateReputation($playerA->user_id, 1);
                $this->Users->updateReputation($playerB->user_id, 1);
            }
        });

        return [
            'a' => $playerASnapShot,
            'b' => $playerBSnapShot
        ];
    }
}
