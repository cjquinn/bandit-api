<?php

namespace App\Model\Table;

use App\Model\Entity\Match;

use ArrayObject;

use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class MatchesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->addAssociations([
            'belongsTo' => [
                'Clubs',
                'PlayerAs' => ['className' => 'Players'],
                'PlayerBs' => ['className' => 'Players']
            ],
            'hasOne' => [
                'Challenges',
                'Disputes',
                'PlayerASnapshots' => [
                    'className' => 'Snapshots',
                    'bindingKey' => ['id', 'player_a_id'],
                    'foreignKey' => ['match_id', 'player_id']
                ],
                'PlayerBSnapshots' => [
                    'className' => 'Snapshots',
                    'bindingKey' => ['id', 'player_b_id'],
                    'foreignKey' => ['match_id', 'player_id']
                ]
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
            ->requirePresence('player_b_id', function ($context) {
                return !isset($context['data']['challenge']);
            })
            ->notEmpty('player_b_id');

        $validator
            ->requirePresence('player_a_score')
            ->notEmpty('player_a_score')
            ->nonNegativeInteger('player_a_score');

        $validator
            ->requirePresence('player_b_score')
            ->notEmpty('player_b_score')
            ->nonNegativeInteger('player_b_score');

        $challengeValidator = new Validator();
        $challengeValidator
            ->requirePresence('id')
            ->notEmpty('id');

        $validator
            ->requirePresence('challenge', function ($context) {
                return !isset($context['data']['player_b_id']);
            })
            ->addNested('challenge', $challengeValidator);

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['player_a_id'], 'PlayerAs'));
        $rules->add($rules->existsIn(['player_b_id'], 'PlayerBs'));

        return $rules;
    }

    /**
     * @return void
     */
    public function patchEntityAdd(Match $match, array $data, $clubId, $playerId)
    {
        $match->setAccess('challenge', true);

        $this->patchEntity($match, $data, ['validate' => 'add']);

        if (!empty($match->getErrors())) {
            return;
        }

        if ($match->challenge) {
            $challenge = $this->Challenges
                ->find()
                ->where([$this->Challenges->aliasField('id') => $data['challenge']['id']])
                ->first();

            if (!$challenge) {
                $match->setError('challenge', [
                    'invalid' => 'This challenge is not valid'
                ]);

                return;
            }

            if ($challenge->club_id !== $clubId) {
                $match->setError('challenge', [
                    'invalid' => 'This challenge is from a different club'
                ]);

                return;
            }

            if ($challenge->player_a_id !== $playerId &&
                $challenge->player_b_id !== $playerId
            ) {
                $match->setError('challenge', [
                    'invalid' => 'This challenge is not yours'
                ]);

                return;
            }

            if (!$challenge->player_b_id) {
                $match->setError('challenge', [
                    'invalid' => 'This challenge hasn\'t been accepted'
                ]);

                return;
            }

            $match->set('challenge', $challenge);

            $otherPlayerIdProperty = $challenge->player_b_id === $playerId ? 'player_a_id' : 'player_b_id';
            $match->set('player_b_id', $challenge->{$otherPlayerIdProperty});
        }

        $match->set('club_id', $clubId);
        $match->set('player_a_id', $playerId);

        if ($match->player_a_id === $match->player_b_id) {
            $match->setError('player_b_id', [
                'invalid' => 'You cannot add matches against yourself'
            ]);

            return;
        }

        if (!$this->Clubs->hasMember($match->club_id, $match->player_b_id, 'id')) {
            $match->setError('player_b_id', [
                'invalid' => 'You can only add matches against members of this club'
            ]);

            return;
        }
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, Match $match)
    {
        if (!$match->deleted) {
            $snapshots = $this->Clubs->Players->snapshotPlayers($match);

            $this->patchEntity($match, $snapshots, [
                'fieldList' => ['player_a_snapshot', 'player_b_snapshot']
            ]);
        }
    }

    /**
     * @return void
     */
    public function saveTree(Match $match)
    {
        $this->connection()->transactional(function () use ($match) {
            // Find tree of affected matches
            $matches = $this
                ->find('tree', ['match' => $match])
                ->find('populated');

            // Revert all players in match tree and resave matches
            $revertedPlayers = [];
            foreach ($matches as $match) {
                foreach (['player_a', 'player_b'] as $player) {
                    $playerId = $match->{$player . '_id'};

                    if (!isset($revertedPlayers[$playerId])) {
                        $revertedPlayers[$playerId] = $this->Clubs->Players->revert($match, $player);
                    }
                }

                $match->setDirty('modified', true);

                $this->save($match);
            }
        });
    }

    /**
     * @return void
     */
    public function softDelete(Match $match)
    {
        $this->connection()->transactional(function () use ($match) {
            $match->set('deleted', new Time());
            $this->save($match);

            $this->saveTree($match);

            TableRegistry::get('Snapshots')->deleteAll([
                'match_id' => $match->id
            ]);
        });
    }

    /**
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        if (!isset($options['ignoreBeforeFind'])) {
            $query->where([$this->aliasField('deleted') . ' IS' => null]);
        }
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findByPlayerId(Query $query, array $options)
    {
        if (isset($options['player_id']) &&
            $options['player_id'] !== 'all'
        ) {
            $query->where([
                'OR' => [
                    $this->aliasField('player_a_id') => $options['player_id'],
                    $this->aliasField('player_b_id') => $options['player_id']
                ]
            ]);
        }

        return $query;
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findPopulated(Query $query, array $options)
    {
        $query->contain([
            'PlayerAs.Users',
            'PlayerASnapshots',
            'PlayerBs.Users',
            'PlayerBSnapshots'
        ]);

        return $query;
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findTree(Query $query, array $options)
    {
        $query
            // Ignore deleted as we are feeding specific tree of matches
            ->find('all', ['ignoreBeforeFind' => true])
            ->where([$this->aliasField('id') . ' IN' => $this->idTree($options['match'])])
            ->orderAsc($this->aliasField('created'));

        return $query;
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findWithBreakdowns(Query $query, array $options)
    {
        $playersTable = TableRegistry::get('Players');

        return $query->formatResults(function ($matches) use ($playersTable) {
            return $matches->map(function ($match) use ($playersTable) {
                // Get daily snapshots first
                $playerADailySnapshot = $playersTable->Snapshots->getDailySnapshot(
                    $match->player_a_id,
                    $match->created->i18nFormat('Y-M-d')
                );
                $playerBDailySnapshot = $playersTable->Snapshots->getDailySnapshot(
                    $match->player_b_id,
                    $match->created->i18nFormat('Y-M-d')
                );

                $expectedScores = $playersTable->expectedScores(
                    $playerADailySnapshot['rating'],
                    $playerBDailySnapshot['rating']
                );

                $playerAKFactor = $playersTable->getKFactor($playerADailySnapshot);
                $match->player_a_breakdown = [
                    'win' => $playersTable->ratingChange($expectedScores['a'], 1, $playerAKFactor),
                    'loss' => $playersTable->ratingChange($expectedScores['a'], 0, $playerAKFactor)
                ];

                $playerBKFactor = $playersTable->getKFactor($playerBDailySnapshot);
                $match->player_b_breakdown = [
                    'win' => $playersTable->ratingChange($expectedScores['b'], 1, $playerBKFactor),
                    'loss' => $playersTable->ratingChange($expectedScores['b'], 0, $playerBKFactor)
                ];

                return $match;
            });
        });
    }

    /**
     * Find a tree of match ids starting with the passed
     * match. When used for softDelete the first match
     * passed is deleted but the rest will not be. This is
     * why it is safe to ignore beforeFind in the findTree method.
     *
     * @return array
     */
    public function idTree(Match $match = null)
    {
        if (!$match) {
            return [];
        }

        $playerIds = [
            $match->player_a_id,
            $match->player_b_id
        ];

        $where = [
            'id !=' => $match->id,
            'club_id' => $match->club_id,
            'created >=' => $match->created
        ];

        $left = $this
            ->find()
            ->where($where + ['player_a_id IN' => $playerIds])
            ->first();

        $right = $this
            ->find()
            ->where($where + ['player_b_id IN' => $playerIds])
            ->first();

        return [$match->id => $match->id] + $this->idTree($left) + $this->idTree($right);
    }

    /**
     * @return bool
     */
    public function isAgainst($id, $userId)
    {
        return (bool)count(
            $this
                ->findById($id)
                ->innerJoinWith('PlayerBs', function ($q) use ($userId) {
                    $q->where(['PlayerBs.user_id' => $userId]);

                    return $q;
                })
                ->limit(1)
                ->enableHydration(false)
                ->toArray()
        );
    }

    /**
     * @return bool
     */
    public function isDisputed($id)
    {
        return $this->Disputes->exists(['match_id' => $id]);
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

    /**
     * @return bool
     */
    public function wasCreatedBy($id, $userId)
    {
        return (bool)count(
            $this
                ->findById($id)
                ->innerJoinWith('PlayerAs', function ($q) use ($userId) {
                    $q->where(['PlayerAs.user_id' => $userId]);

                    return $q;
                })
                ->limit(1)
                ->enableHydration(false)
                ->toArray()
        );
    }

    /**
     * @return void
     */
    public function wasWithinLast($id, $period)
    {
        return $this->exists([
            'id' => $id,
            'created >=' => new Time('-' . $period)
        ]);
    }
}
