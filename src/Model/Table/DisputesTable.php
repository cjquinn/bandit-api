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

        $this->primaryKey('result_id');
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
        $rules->add($rules->existsIn(['result_id'], 'Results'));

        return $rules;
    }

    /**
     * @return void
     */
    public function afterSave(Event $event, Dispute $dispute, ArrayObject $options)
    {
        if (!$dispute->isNew()) {
            if (!$dispute->result) {
                $this->loadInto($dispute, [
                    'Results' => [
                        'LosingPlayers',
                        'WinningPlayers'
                    ]
                ]);
            }

            if ($dispute->result->created->wasWithinLast('48 hours')) {
                $reputationDifference = $dispute->is_resolved ? -1 : -11;

                $this->Results->Players->updateReputation($dispute->result->losing_player, $reputationDifference);
                $this->Results->Players->updateReputation($dispute->result->winning_player, $reputationDifference);
            } else {
                $this->Results->Players->updateReputation($dispute->result->winning_player, -11);
            }

            $this->Results->Players->save($dispute->result->losing_player);
            $this->Results->Players->save($dispute->result->winning_player);

            $this->Results->nullify($dispute->result);
        }
    }
}
