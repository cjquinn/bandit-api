<?php

namespace App\Model\Table;

use App\Model\Entity\Result;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
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
                'Logins',
                'Losers' => [
                    'className' => 'Players',
                    'foreignKey' => 'loser_id'
                ],
                'Players',
                'Winners' => [
                    'className' => 'Players',
                    'foreignKey' => 'winner_id'
                ]
            ],
            'hasOne' => [
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
            ],
            'hasMany' => [
                'Histories'
            ]
        ]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('loser_id', 'create')
            ->notEmpty('loser_id');

        $validator
            ->requirePresence('date', 'create')
            ->notEmpty('date')
            ->add('date', 'valid', [
                'rule' => 'datetime'
            ]);

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['loser_id'], 'Losers'));
        $rules->add($rules->existsIn(['winner_id'], 'Winners'));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, Result $result, ArrayObject $options)
    {
        if ($result->isNew()) {
            $this->Players->updateRatings($result);

            $this->patchEntity($result, [
                'losers_history' => [
                    'player_id' => $result->losing_player->id,
                    'difference' => $result->losing_player->rating - $result->losing_player->getOriginal('rating'),
                    'rating' => $result->losing_player->rating
                ],
                'winners_history' => [
                    'player_id' => $result->winning_player->id,
                    'difference' => $result->winning_player->rating - $result->winning_player->getOriginal('rating'),
                    'rating' => $result->winning_player->rating
                ]
            ]);
        }
    }
}
