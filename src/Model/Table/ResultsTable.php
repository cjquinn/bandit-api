<?php

namespace App\Model\Table;

use App\Model\Entity\Player;
use App\Model\Entity\Result;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ResultsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Players'
            ],
            'hasOne' => [
                'Disputes',
                'Histories',
                'LosersHistories' => [
                    'className' => 'Histories',
                    'conditions' => [
                        'LosersHistories.difference <' => 0
                    ]
                ],
                'WinnersHistories' => [
                    'className' => 'Histories',
                    'conditions' => [
                        'WinnersHistories.difference >' => 0
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
        if ($result->isNew()) {
            $result->accessible('losers_history', true);
            $result->accessible('winners_history', true);

            $losingPlayer = $this->Players->get($result->losing_player_id);
            $winningPlayer = $this->Players->get($result->winning_player_id);

            $this->Players->updateRatings($losingPlayer, $winningPlayer);

            $this->patchEntity($result, [
                'losers_history' => [
                    'player' => $losingPlayer,
                ],
                'winners_history' => [
                    'player' => $winningPlayer
                ]
            ], [
                'validate' => false
            ]);
        }
    }
}
