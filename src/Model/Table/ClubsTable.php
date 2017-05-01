<?php

namespace App\Model\Table;

use App\Model\Entity\Club;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ClubsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Founders' => ['className' => 'Users']
            ],
            'hasMany' => [
                'Players',
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('founder', function ($context) {
                return !isset($context['data']['founder_id']) && $context['newRecord'];
            })
            ->notEmpty('founder');

        $validator
            ->requirePresence('founder_id', function ($context) {
                return !isset($context['data']['founder']) && $context['newRecord'];
            })
            ->notEmpty('founder_id');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['founder_id'], 'Founders'));

        return $rules;
    }

    /**
     * @return void
     */
    public function afterSave(Event $event, Club $club, ArrayObject $options)
    {
        if ($club->isNew()) {
            $this->patchEntity($club, [
                'players' => [
                    ['user_id' => $club->founder_id]
                ]
            ], [
                'fieldList' => ['players'],
                'validate' => false
            ]);

            $this->save($club);
        }
    }

    /**
     * @return bool
     */
    public function isOwnedBy($clubId, $userId)
    {
        return $this->exists([
            'id' => $clubId,
            'founder_id' => $userId
        ]);
    }

    /**
     * @return bool
     */
    public function hasMember($clubId, $userId)
    {
        return $this->Players->exists([
            'club_id' => $clubId,
            'user_id' => $userId
        ]);
    }
}
