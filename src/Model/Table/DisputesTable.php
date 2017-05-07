<?php

namespace App\Model\Table;

use App\Model\Entity\Dispute;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class DisputesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Results'
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
            ->requirePresence('player_a_score', 'create')
            ->notEmpty('player_a_score')
            ->nonNegativeInteger('player_a_score');

        $validator
            ->requirePresence('player_b_score', 'create')
            ->notEmpty('player_b_score')
            ->nonNegativeInteger('player_b_score');

        $validator
            ->requirePresence('message', 'create')
            ->allowEmpty('message');

        $validator
            ->requirePresence('is_resolved', 'update')
            ->notEmpty('is_resolved')
            ->boolean('is_resolved');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['result_id'], 'Results'));

        return $rules;
    }

    /**
     * @return bool
     */
    public function isClosed($id)
    {
        return $this->exists([
            'id' => $id,
            'is_resolved IS NOT' => null
        ]);
    }
}
