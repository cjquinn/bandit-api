<?php

namespace App\Model\Table;

use App\Model\Entity\Dispute;

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
     * @return void
     */
    public function patchEntityAdd(Dispute $dispute, array $data)
    {
        $this->patchEntity($dispute, $data, [
            'fieldList' => [
                'player_a_score',
                'player_b_score',
                'message'
            ],
            'validate' => 'add'
        ]);
    }

    /**
     * @return void
     */
    public function patchEntityEdit(Dispute $dispute, array $data)
    {
        $this->patchEntity($dispute, $data, [
            'fieldList' => ['is_resolved'],
            'validate' => 'edit'
        ]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
        $validator
            ->requirePresence('player_a_score')
            ->notEmpty('player_a_score')
            ->nonNegativeInteger('player_a_score');

        $validator
            ->requirePresence('player_b_score')
            ->notEmpty('player_b_score')
            ->nonNegativeInteger('player_b_score');

        $validator
            ->requirePresence('message')
            ->allowEmpty('message');

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationEdit(Validator $validator)
    {
        $validator
            ->requirePresence('is_resolved')
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
     * @return void
     */
    public function close(Dispute $dispute)
    {
        $this->connection()->transactional(function () use ($dispute) {
            $result = $this->Results->get($dispute->result_id, [
                'contain' => [
                    'PlayerAs',
                    'PlayerBs'
                ]
            ]);

            if (!$result->created->wasWithinLast('48 hours')) {
                $this->Results->softDelete($result);

                $this->Results->PlayerAs->Users->updateReputation($result->player_a->user_id, -10);
                return;
            }

            if (!$dispute->is_resolved) {
                $this->Results->softDelete($result);
                $this->Results->PlayerAs->Users->updateReputation($result->player_a->user_id, -10);
                $this->Results->PlayerBs->Users->updateReputation($result->player_b->user_id, -10);
                return;
            }

            $result->set('player_a_score', $dispute->player_a_score);
            $result->set('player_b_score', $dispute->player_b_score);

            $this->Results->save($result);
            $this->Results->saveTree($result);
        });
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
