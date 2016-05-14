<?php
namespace App\Model\Table;

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
}
