<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class RacketwareSessionsTable extends Table
{
    /**
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->hasMany('Snapshots');

        $this->addBehavior('Timestamp');
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
        $validator
            ->requirePresence('player')
            ->notEmpty('player')
            ->nonNegativeInteger('player');

        $validator
            ->requirePresence('action')
            ->notEmptyString('action')
            ->inList('action', ['upload']);

        $validator
            ->requirePresence('data')
            ->notEmptyArray('data');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['racketware_player_id'], 'RacketwarePlayers'));

        return $rules;
    }
}
