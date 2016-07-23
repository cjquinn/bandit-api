<?php

namespace App\Model\Table;

use App\Model\Entity\Result;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use DateTime;

class ResultsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'BoxMatches',
                'Clubs',
                'LosingPlayers' => [
                    'className' => 'Players',
                    'foreignKey' => 'losing_player_id'
                ],
                'Players',
                'WinningPlayers' => [
                    'className' => 'Players',
                    'foreignKey' => 'winning_player_id'
                ]
            ],
            'hasOne' => [
                'Disputes' => [
                    'dependent' => true
                ],
                'Histories' => [
                    'foreignKey' => 'result_id',
                ],
                'LosingPlayerHistories' => [
                    'className' => 'Histories',
                    'foreignKey' => 'result_id',
                    'conditions' => [
                        'LosingPlayerHistories.is_winner' => false
                    ]
                ],
                'WinningPlayerHistories' => [
                    'className' => 'Histories',
                    'foreignKey' => 'result_id',
                    'conditions' => [
                        'WinningPlayerHistories.is_winner' => true
                    ]
                ]
            ]
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'submitted' => 'new'
                ]
            ]
        ]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('losing_player_id', 'create')
            ->nonNegativeInteger('losing_player_id');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['box_match_id'], 'BoxMatches'));
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['losing_player_id'], 'Players'));
        $rules->add($rules->existsIn(['winning_player_id'], 'Players'));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, Result $result, ArrayObject $options)
    {
        if (!isset($options['ignoreEvents'])) {
            $losingPlayer = $this->Players->get($result->losing_player_id);
            $winningPlayer = $this->Players->get($result->winning_player_id);

            if ($result->isNew()) {
                $this->Players->updateReputation($losingPlayer, 1);
                $this->Players->updateReputation($winningPlayer, 1);
            } elseif (!$result->losing_player_history || !$result->winning_player_history) {
                $this->loadInto($result, [
                    'LosingPlayerHistories',
                    'WinningPlayerHistories'
                ]);
            }

            $date = $result->isNew() ? new DateTime('today') : new DateTime($result->submitted->i18nFormat('Y-M-d'));
            $this->Players->updateRatings($losingPlayer, $winningPlayer, $result->club_id, $date);

            $this->patchEntity($result, [
                'losing_player_history' => [
                    'player' => $losingPlayer,
                ],
                'winning_player_history' => [
                    'player' => $winningPlayer
                ]
            ], [
                'fieldList' => [
                    'losing_player_history',
                    'winning_player_history'
                ],
                'validate' => false
            ]);
        }
    }

    /**
     * @return void
     */
    public function beforeDelete(Event $event, Result $result, ArrayObject $options)
    {
        $resultIds = function ($result) use (&$resultIds) {
            if (!$result) {
                return [];
            }

            $playerIds = [
                $result['losing_player_id'],
                $result['winning_player_id']
            ];
            $select = [
                'id',
                'losing_player_id',
                'winning_player_id',
                'submitted'
            ];
            $where = [
                'id !=' => $result['id'],
                'submitted >=' => $result['submitted']
            ];

            $left = $this
                ->find()
                ->select($select)
                ->where($where + ['losing_player_id IN' => $playerIds])
                ->hydrate(false)
                ->first();

            $right = $this
                ->find()
                ->select($select)
                ->where($where + ['winning_player_id IN' => $playerIds])
                ->hydrate(false)
                ->first();

            return [$result['id'] => $result['id']] + $resultIds($left) + $resultIds($right);
        };

        $results = $this
            ->find()
            ->where([
                'Results.id IN' => $resultIds($result)
            ])
            ->contain([
                'LosingPlayerHistories.Players.Club' => function ($q) use ($result) {
                    $q->where([
                        'Club.club_id' => $result->club_id
                    ]);

                    return $q;
                },
                'WinningPlayerHistories.Players.Club' => function ($q) use ($result) {
                    $q->where([
                        'Club.club_id' => $result->club_id
                    ]);

                    return $q;
                }
            ])
            ->order([
                'submitted' => 'ASC'
            ]);

        $revertedPlayers = [];

        $revertPlayer = function ($history) use (&$revertedPlayers) {
            if (!isset($revertedPlayers[$history->player->id])) {
                $snapshot = [
                    'rating' => $history['snapshot']['rating'] - $history['snapshot']['difference']
                ];

                if ($history->is_winner) {
                    $snapshot['losses'] = $history['snapshot']['losses'];
                    $snapshot['wins'] = $history['snapshot']['wins'] - 1;
                } else {
                    $snapshot['losses'] = $history['snapshot']['losses'] - 1;
                    $snapshot['wins'] = $history['snapshot']['wins'];
                }

                $history->player->club->set($snapshot, [
                    'guard' => false
                ]);

                $this->Players->Club->save($history->player->club);

                $revertedPlayers[$history->player->id] = true;
            }
        };

        $results = $results
            ->filter(function ($r) use ($result, $revertPlayer) {
                $revertPlayer($r->losing_player_history);
                $revertPlayer($r->winning_player_history);

                if ($r->id === $result->id) {
                    $this->Histories->delete($r->losing_player_history);
                    $this->Histories->delete($r->winning_player_history);

                    return false;
                }
                
                return true;
            })
            ->toArray();

        foreach ($results as $result) {
            $result->dirty('*', true);

            $this->save($result);
        }
    }

    /**
     * @return void
     */
    public function afterDelete(Event $event, Result $result, ArrayObject $options)
    {
        $players = $this->Players
            ->find()
            ->where([
                'id IN' => [
                    $result->losing_player_id,
                    $result->winning_player_id
                ]
            ]);

        foreach ($players as $player) {
            $this->Players->updateReputation($player, -1);

            $this->Players->save($player);
        }
    }

    /**
     * @return bool
     */
    public function isBoxGame($id)
    {
        return $this->exists([
            'id' => $id,
            'box_match_id IS NOT' => null
        ]);
    }

    /**
     * @return bool
     */
    public function isDisputed($id)
    {
        return $this->Disputes->exists([
            'result_id' => $id
        ]);
    }

    /**
     * @return bool
     */
    public function isOwnedBy($id, $winningPlayerId)
    {
        return $this->exists([
            'id' => $id,
            'winning_player_id' => $winningPlayerId
        ]);
    }

    /**
     * @return void
     */
    public function wasWithinLast($id, $period)
    {
        return $this->exists([
            'id' => $id,
            'submitted >=' => new DateTime('-' . $period)
        ]);
    }
}
