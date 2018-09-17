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
            ],
            'hasMany' => [
                'Snapshots'
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

        if ($user) {
            $player->set('user', $user);
        }

        if (!$player->user->is_activated) {
            $this->Users->patchEntitySetToken($player->user);

            return;
        }

        if ($this->Clubs->hasMember($player->club_id, $player->user->id)) {
            $player->user->setError('email', [
                'duplicate' => 'A member of this club already exists with that email'
            ]);

            return;
        }

        $player->set('user_id', $player->user->id);
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
            $snapshot = $match->{$player . '_snapshot'}->stats;
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
    public function snapshotPlayer(Player $player, $expectedScore, $wins, $losses)
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
    public function snapshotPlayers(Match $match)
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
            $playerADailySnapshot = $this->Snapshots->getDailySnapshot(
                $playerA->id,
                $date
            );
            $playerBDailySnapshot = $this->Snapshots->getDailySnapshot(
                $playerB->id,
                $date
            );

            // Expected scores from daily rating
            $expectedScores = $this->expectedScores(
                $playerADailySnapshot['rating'],
                $playerBDailySnapshot['rating']
            );

            // Update player stats
            $playerASnapShot = $this->snapshotPlayer(
                $playerA,
                $expectedScores['a'],
                $match->player_a_score,
                $match->player_b_score
            );
            $playerBSnapShot = $this->snapshotPlayer(
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
            'player_a_snapshot' => $playerASnapShot,
            'player_b_snapshot' => $playerBSnapShot
        ];
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findAllTimeLeaderboard(Query $query, array $options)
    {
        $query
            ->orderDesc($this->aliasField('rating'))
            ->orderDesc($this->aliasField('wins'))
            ->orderAsc($this->aliasField('losses'))
            ->orderDesc($this->aliasField('modified'));

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
     * @return void
     */
    public function findWeeklyLeaderboard(Query $query, array $options)
    {
        $startOfWeek = new Time('monday this week');

        $playerIds = $this->Snapshots
            ->find()
            ->select('player_id')
            ->where(['created >=' => $startOfWeek]);

        $matchId = $this->Snapshots
            ->find()
            ->select('match_id')
            ->where([
                'player_id = Players.id',
                'created <' => $startOfWeek
            ])
            ->orderDesc('created')
            ->limit(1);

        $query
            ->select([
                // 'Snapshots.rating',
                'rating_change' => $this->find()->newExpr(
                    sprintf(
                        '%s - COALESCE(Snapshots.rating, %s)',
                        $this->aliasField('rating'),
                        Configure::read('Bandit.initialRating')
                    )
                ),
                'wins_change' => $this->find()->newExpr(
                    sprintf(
                        '%s - COALESCE(Snapshots.wins, %s)',
                        $this->aliasField('wins'),
                        0
                    )
                ),
                'losses_change' => $this->find()->newExpr(
                    sprintf(
                        '%s - COALESCE(Snapshots.losses, %s)',
                        $this->aliasField('losses'),
                        0
                    )
                )
            ])
            ->enableAutoFields(true)
            ->join([
                'Snapshots' => [
                    'table' => 'snapshots',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Snapshots.player_id = Players.id',
                        'Snapshots.match_id' => $matchId
                    ]
                ]
            ])
            ->where([$this->aliasField('id') . ' IN' => $playerIds])
            ->orderDesc('rating_change')
            ->orderDesc('wins_change')
            ->orderAsc('losses_change');

        return $query;
    }

    /**
     * @return void
     */
    public function findWithHighestRating(Query $query, array $options)
    {
        $matchId = $this->Snapshots
            ->find()
            ->select('match_id')
            ->where(['player_id = Players.id'])
            ->orderDesc('rating')
            ->limit(1);

        $query
            ->select([
                'highest_rating' => $this->find()->newExpr(
                    sprintf(
                        'GREATEST(%s, COALESCE(Snapshots.rating, %s))',
                        Configure::read('Bandit.initialRating'),
                        Configure::read('Bandit.initialRating')
                    )
                )
            ])
            ->enableAutoFields(true)
            ->join([
                'Snapshots' => [
                    'table' => 'snapshots',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Snapshots.player_id = Players.id',
                        'Snapshots.match_id' => $matchId
                    ]
                ]
            ]);

        return $query->formatResults(function ($results) {
            return $results->map(function ($player) {
                $player->highest_level = $player->highest_level;

                return $player;
            });
        });
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
