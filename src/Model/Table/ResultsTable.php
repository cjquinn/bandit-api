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
                        'LosingPlayersHistories.difference <' => 0
                    ]
                ],
                'WinningPlayersHistories' => [
                    'className' => 'Histories',
                    'conditions' => [
                        'WinningPlayersHistories.difference >' => 0
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
    public function beforeSave(Event $event, Result $result, ArrayObject $options)
    {
        $losingPlayer = $this->Players->get($result->losing_player_id);
        $winningPlayer = $this->Players->get($result->winning_player_id);

        if ($result->isNew()) {
            $this->Players->updateReputation($losingPlayer, 1);
            $this->Players->updateReputation($winningPlayer, 1);
        } elseif (!$result->losing_players_history || !$result->winning_player_id) {
            $this->loadInto($result, [
                'LosingPlayersHistories',
                'WinningPlayersHistories'
            ]);
        }

        $date = $result->isNew() ? new DateTime('today') : new DateTime($result->created->i18nFormat('Y-M-d'));
        $this->Players->updateRatings($losingPlayer, $winningPlayer, $date);

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
     * @param \App\Model\Entity\Result $result The result
     * @return void
     */
    public function nullify(Result $result)
    {
        // Date of result
        $date = new DateTime($result->created->i18nFormat('Y-M-d'));

        // Get effected results
        $results = $this
            ->find()
            ->contain([
                'LosingPlayersHistories.Players',
                'WinningPlayersHistories.Players'
            ])
            ->innerJoinWith('LosingPlayersHistories')
            ->where([
                'created >=' => $date
            ]);

        // Update ratings to daily rating of result
        $players = [];
        $results = $results->map(function ($r) use ($date, &$players, $result) {
            // Update losing player if not already done
            if (!isset($players[$r->losing_player_id])) {
                $r->losing_players_history->player->set('rating', $this->Players->dailyRating($r->losing_players_history->player, $date));
                $this->Players->save($r->losing_players_history->player);

                $players[$r->losing_player_id] = true;
            }

            // Update winning player if not already done
            if (!isset($players[$r->winning_player_id])) {
                $r->winning_players_history->player->set('rating', $this->Players->dailyRating($r->winning_players_history->player, $date));
                $this->Players->save($r->winning_players_history->player);

                $players[$r->winning_player_id] = true;
            }

            if ($r->id === $result->id) {
                // Remove history
                $this->Histories->delete($r->losing_players_history);
                $this->Histories->delete($r->winning_players_history);

                return;
            }
            
            return $r;
        });

        // Re-save results
        $results->each(function ($result) {
            if ($result) {
                $result->dirty('*', true);
                $this->save($result);
            }
        });
    }
}
