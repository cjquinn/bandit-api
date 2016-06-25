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
                'Boxes',
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
                'Disputes',
                'Histories',
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
        $rules->add($rules->existsIn(['box_id'], 'Boxes'));
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['losing_player_id'], 'Players'));
        $rules->add($rules->existsIn(['winning_player_id'], 'Players'));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeDelete(Event $event, Result $result, ArrayObject $options)
    {
        $this->nullify($result);
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, Result $result, ArrayObject $options)
    {
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
     * @param \App\Model\Entity\Result $result The result
     * @return void
     */
    public function nullify(Result $result)
    {
        $clubId = $result->club_id;
        $date = new DateTime($result->submitted->i18nFormat('Y-M-d'));
        $players = [];
        $results = $this
            ->find()
            ->contain([
                'LosingPlayerHistories.Players.Club' => function ($q) use ($clubId) {
                    $q->where([
                        'Club.club_id' => $clubId
                    ]);

                    return $q;
                },
                'WinningPlayerHistories.Players.Club' => function ($q) use ($clubId) {
                    $q->where([
                        'Club.club_id' => $clubId
                    ]);

                    return $q;
                }
            ])
            ->innerJoinWith('LosingPlayerHistories')
            ->where([
                'submitted >=' => $date
            ]);

        
        $revertPlayer = function ($player) use ($clubId, $date, &$players) {
            if (!isset($players[$player->id])) {
                $player->club->set($this->Players->dailySnapshot($player, $clubId, $date), [
                    'guard' => false
                ]);
                $this->Players->Club->save($player->club);

                $players[$player->id] = true;
            }
        };

        $results = $results->filter(function ($r) use ($result, $revertPlayer) {
            $revertPlayer($r->losing_player_history->player);
            $revertPlayer($r->winning_player_history->player);

            if ($r->id === $result->id) {
                $this->Histories->delete($r->losing_player_history);
                $this->Histories->delete($r->winning_player_history);

                return false;
            }
            
            return true;
        });

        foreach ($results->toArray() as $result) {
            $result->dirty('*', true);
            $this->save($result);
        }
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
