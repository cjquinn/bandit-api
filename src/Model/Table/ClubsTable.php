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
                'FoundingPlayers' => [
                    'className' => 'Players',
                    'foreignKey' => 'founding_player_id'
                ]
            ],
            'belongsToMany' => [
                'Players'
            ],
            'hasMany' => [
                'Results'
            ]
        ]);
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
            ->requirePresence('founding_player', function ($context) {
                return !isset($context['data']['founding_player_id']) && $context['newRecord'];
            })
            ->notEmpty('founding_player');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['founding_player_id'], 'Players'));

        return $rules;
    }

    /**
     * TODO: move this into beforeSave
     *
     * @return void
     */
    public function afterSave(Event $event, Club $club, ArrayObject $options)
    {
        if ($club->isNew()) {
            $this->patchEntity($club, [
                'players' => [
                    '_ids' => [
                        $club->founding_player_id
                    ]
                ]
            ], [
                'fieldList' => [
                    'players'
                ],
                'validate' => false
            ]);

            $this->save($club);
        }
    }
}
