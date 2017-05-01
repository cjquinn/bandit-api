<?php

namespace App\Model\Table;

use App\Model\Entity\Result;

use ArrayObject;

use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
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

        $this->addBehavior('Timestamp');
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

            if (!$result->submitted) {
                $result->set('submitted', new Time());
            }

            $date = new DateTime($result->submitted->i18nFormat('Y-M-d'));
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
        $results = $this->find('tree', [
            'result' => $result
        ]);

        $revertedPlayers = [];

        $results = $results->filter(function ($r) use ($result, &$revertedPlayers) {
            if (!isset($revertedPlayers[$r->losing_player_history->player_id])) {
                $this->Players->revert($r->losing_player_history);
                $revertedPlayers[$r->losing_player_history->player_id] = true;
            }

            if (!isset($revertedPlayers[$r->winning_player_history->player_id])) {
                $this->Players->revert($r->winning_player_history);
                $revertedPlayers[$r->winning_player_history->player_id] = true;
            }

            if ($r->id === $result->id) {
                $this->Histories->delete($r->losing_player_history);
                $this->Histories->delete($r->winning_player_history);

                return false;
            }

            return true;
        });

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
     * @return \Cake\ORM\Query
     */
    public function findTree(Query $query, array $options)
    {
        $query
            ->where([
                'Results.id IN' => $this->idTree($options['result'])
            ])
            ->contain([
                'LosingPlayerHistories',
                'WinningPlayerHistories'
            ])
            ->order([
                'submitted' => 'ASC'
            ]);

        return $query;
    }

    /**
     * @param null|\App\Model\Entity\Result $result The result
     * @return array
     */
    public function idTree($result)
    {
        if (!$result) {
            return [];
        }

        $playerIds = [
            $result->losing_player_id,
            $result->winning_player_id
        ];
        $where = [
            'club_id' => $result->club_id,
            'submitted >' => $result->submitted
        ];

        if ($result->id) {
            $where['id !='] = $result->id;
        }

        $left = $this
            ->find()
            ->where($where + ['losing_player_id IN' => $playerIds])
            ->first();

        $right = $this
            ->find()
            ->where($where + ['winning_player_id IN' => $playerIds])
            ->first();

        if ($result->id) {
            return [$result->id => $result->id] + $this->idTree($left) + $this->idTree($right);
        }

        return $this->idTree($left) + $this->idTree($right);
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
