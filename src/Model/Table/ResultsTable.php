<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PlayersTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Logins',
                'Receivers' => [
                    'className' => 'Players',
                    'foreignKey' => 'receiver_id'
                ],
                'Senders' => [
                    'className' => 'Players',
                    'foreignKey' => 'sender_id'
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
            ->requirePresence('receiver_id', 'create')
            ->notEmpty('receiver_id');

        $validator
            ->requirePresence('date', 'create')
            ->notEmpty('date')
            ->add('date', 'valid', ['rule' => 'datetime']);

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['receiver_id'], 'Receivers'));
        $rules->add($rules->existsIn(['sender_id'], 'Senders'));
        $rules->add($rules->existsIn(['winner_id'], 'Winners'));

        return $rules;
    }
}
