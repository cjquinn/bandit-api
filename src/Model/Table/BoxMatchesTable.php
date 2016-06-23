<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class BoxMatchesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->primaryKey(['box_id', 'losing_player_id', 'winning_player_id']);

        $this->addAssociations([
            'belongsTo' => [
                'Boxes',
                'LosingPlayers' => [
                    'className' => 'Players',
                    'foreignKey' => 'losing_player_id'
                ],
                'Players',
                'WinningPlayers' => [
                    'className' => 'Players',
                    'foreignKey' => 'winning_player_id'
                ]
            ]
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'BoxMatch.disputed' => [
                    'disputed' => 'always'
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

        $valid = function ($value, $context) {
            return isset($context['data']['losses']) && isset($context['data']['wins']) && $context['data']['losses'] <= $context['data']['wins'];
        };

        $validator
            ->requirePresence('losses', 'create')
            ->add('losses', [
                'nonNegativeInteger' => [
                    'rule' => ['naturalNumber', true],
                    'last' => true
                ],
                'lessThanOrEqual' => [
                    'rule' => ['comparison', '<=', 2],
                ],
                'valid' => [
                    'rule' => $valid,
                    'message' => 'You must enter more wins then losses'
                ]
            ]);

        $validator
            ->requirePresence('wins', 'create')
            ->add('wins', [
                'nonNegativeInteger' => [
                    'rule' => ['naturalNumber', true],
                    'last' => true
                ],
                'greaterThanOrEqual' => [
                    'rule' => ['comparison', '>=', 1],
                ],
                'lessThanOrEqual' => [
                    'rule' => ['comparison', '<=', 3],
                ],
                'valid' => [
                    'rule' => $valid,
                    'message' => 'You must enter more wins then losses'
                ]
            ]);

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['box_id'], 'Boxes'));
        $rules->add($rules->existsIn(['losing_player_id'], 'Players'));
        $rules->add($rules->existsIn(['winning_player_id'], 'Players'));

        return $rules;
    }
}
