<?php

namespace App\Model\Table;

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
                'Winners' => [
                    'className' => 'Players',
                    'foreignKey' => 'winner_id'
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
}
