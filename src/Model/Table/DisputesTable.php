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
                'Players',
                'Results'
            ]
        ]);

        $this->primaryKey([
            'player_id',
            'result_id'
        ]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('message', 'create')
            ->allowEmpty('message');

        $validator
            ->requirePresence('is_resolved', 'update')
            ->notEmpty('is_resolved')
            ->add('is_resolved', 'boolean', [
                'rule' => 'boolean',
                'message' => 'This is a boolean flag'
            ]);

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['player_id'], 'Players'));
        $rules->add($rules->existsIn(['result_id'], 'Results'));

        return $rules;
    }

    /**
     * @return void
     */
    public function afterSave(Event $event, Dispute $dispute, ArrayObject $options)
    {
        if (!$dispute->isNew()) {
            $this->Results->nullify($dispute->result_id);
        }
    }
}
