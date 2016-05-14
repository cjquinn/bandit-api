<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class BoxLeaguesTable extends Table
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('start', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('start');

        $validator
            ->add('end', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('end');

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
