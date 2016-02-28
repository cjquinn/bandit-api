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

        $this->Players->updateRatings($losingPlayer, $winningPlayer, new DateTime($result->created->i18nFormat('Y-M-d')));

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
     * @param \App\Model\Entity\Result $result The result object
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
        $nullifyId = $result->id;
        $players = [];
        $results = $results->map(function ($result) use ($date, $nullifyId, &$players) {
            if (!isset($players[$result->losing_player_id])) {
                $result->losing_players_history->player->set('rating', $this->Players->dailyRating($result->losing_players_history->player, $date));
                $this->Players->save($result->losing_players_history->player);

                $players[$result->losing_player_id] = $result->losing_players_history->player;
            }

            if (!isset($players[$result->winning_player_id])) {
                $result->winning_players_history->player->set('rating', $this->Players->dailyRating($result->winning_players_history->player, $date));
                $this->Players->save($result->winning_players_history->player);

                $players[$result->winning_player_id] = $result->winning_players_history->player;
            }

            if ($result->id === $nullifyId) {
                // Remove history
                $this->Histories->delete($result->losing_players_history);
                $this->Histories->delete($result->winning_players_history);
                
                return;
            }
            
            return $result;
        });

        // Resave results
        $results->each(function ($result) {
            if ($result) {
                $result->dirty('*', true);
                $this->save($result);
            }
        });
    }
}
