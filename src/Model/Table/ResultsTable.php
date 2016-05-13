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
                'LosingPlayersHistories' => [
                    'className' => 'Histories',
                    'conditions' => [
                        'LosingPlayersHistories.is_winner' => false
                    ]
                ],
                'WinningPlayersHistories' => [
                    'className' => 'Histories',
                    'conditions' => [
                        'WinningPlayersHistories.is_winner' => true
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
            ->notEmpty('losing_player_id');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
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
        } elseif (!$result->losing_players_history || !$result->winning_players_history) {
            $this->loadInto($result, [
                'LosingPlayersHistories',
                'WinningPlayersHistories'
            ]);
        }

        $date = $result->isNew() ? new DateTime('today') : new DateTime($result->created->i18nFormat('Y-M-d'));
        $this->Players->updateRatings($losingPlayer, $winningPlayer, $result->club_id, $date);

        $this->patchEntity($result, [
            'losing_players_history' => [
                'player' => $losingPlayer,
            ],
            'winning_players_history' => [
                'player' => $winningPlayer
            ]
        ], [
            'fieldList' => [
                'losing_players_history',
                'winning_players_history'
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
        $date = new DateTime($result->created->i18nFormat('Y-M-d'));
        $players = [];
        $results = $this
            ->find()
            ->contain([
                'LosingPlayersHistories.Players.Club' => function ($q) use ($clubId) {
                    $q->where([
                        'Club.club_id' => $clubId
                    ]);

                    return $q;
                },
                'WinningPlayersHistories.Players.Club' => function ($q) use ($clubId) {
                    $q->where([
                        'Club.club_id' => $clubId
                    ]);

                    return $q;
                }
            ])
            ->innerJoinWith('LosingPlayersHistories')
            ->where([
                'created >=' => $date
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

        $results = $results->map(function ($r) use ($result, $revertPlayer) {
            $revertPlayer($r->losing_players_history->player);
            $revertPlayer($r->winning_players_history->player);

            if ($r->id === $result->id) {
                $this->Histories->delete($r->losing_players_history);
                $this->Histories->delete($r->winning_players_history);

                return;
            }
            
            return $r;
        });

        $results->each(function ($result) {
            if ($result) {
                $result->dirty('*', true);
                $this->save($result);
            }
        });
    }

    /**
     * @return void
     */
    public function wasWithinLast($id, $period)
    {
        return $this->exists([
            'id' => $id,
            'created >=' => new DateTime('-' . $period)
        ]);
    }
}
