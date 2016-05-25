<?php
namespace App\Model\Table;

use App\Model\Entity\BoxLeagueCycle;

use ArrayObject;

use Cake\Event\Event;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class BoxLeagueCyclesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Clubs'
            ],
            'hasMany' => [
                'Boxes'
            ]
        ]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationStartCycle(Validator $validator)
    {
        $validator
            ->requirePresence('start')
            ->notEmpty('start')
            ->add('start', 'valid', ['rule' => 'datetime']);

        $validator
            ->requirePresence('end')
            ->notEmpty('end')
            ->add('end', 'valid', ['rule' => 'datetime']);

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        
        return $rules;
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, BoxLeagueCycle $boxLeagueCycle, ArrayObject $options)
    {
        if ($boxLeagueCycle->isNew()) {
            $this->patchEntity($boxLeagueCycle, [
                'boxes' => [
                    [
                        'division' => 1
                    ],
                    [
                        'division' => 2
                    ]
                ]
            ], [
                'fieldList' => ['boxes'],
                'associated' => [
                    'Boxes' => [
                        'fieldList' => 'division'
                    ]
                ]
            ]);
        }
    }

    /**
     * Application rules
     *
     * A box must have at least 2 boxes to run
     */
}
