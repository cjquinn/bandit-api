<?php
namespace App\Model\Table;

use App\Model\Entity\BoxLeagueCycle;

use ArrayObject;

use Cake\Chronos\Date;
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
            ->add('start', [
                'date' => [
                    'rule' => ['date'],
                    'last' => true
                ],
                'custom' => [
                    'on' => function ($context) {
                        return isset($context['data']['start']) && isset($context['data']['end']);
                    },
                    'message' => 'Start date cannot be in the past or after the end date.',
                    'rule' => function ($value, $context) {
                        $start = new Date($context['data']['start']);
                        $end = new Date($context['data']['end']);

                        return $start->gte(Date::now()) && $start->lt($end);
                    }
                ]
            ]);

        $validator
            ->requirePresence('end')
            ->add('end', [
                'date' => [
                    'rule' => ['date'],
                    'last' => true
                ],
                'custom' => [
                    'on' => function ($context) {
                        return isset($context['data']['start']) && isset($context['data']['end']);
                    },
                    'message' => 'The end date must be after the start date.',
                    'rule' => function ($value, $context) {
                        $start = new Date($context['data']['start']);
                        $end = new Date($context['data']['end']);

                        return $end->gt($start);
                    }
                ]
            ]);

        $validator
            ->requirePresence('boxes')
            ->hasAtLeast('boxes', 2);

        $boxValidator = new Validator();
        $boxValidator->hasAtLeast('players', 4);
        
        $validator->addNestedMany('boxes', $boxValidator);

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
                    ['division' => 1],
                    ['division' => 2]
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
     * @return bool
     */
    public function hasMinimumBoxes($id)
    {
        return $this->Boxes->findByBoxLeagueCycleId($id)->count() === 2;
    }

    /**
     * @return bool
     */
    public function isOwnedBy($id, $clubId)
    {
        return $this->exists([
            'id' => $id,
            'club_id' => $clubId
        ]);
    }
}
