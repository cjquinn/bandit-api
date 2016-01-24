<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class FilesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssocations([
            'belongsTo' => [
                'Players'
            ]
        ]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator->add('file', 'file', [
            'rule' => [
                'uploadedFile',
                [
                    'types' => [
                        'image/jpeg',
                        'image/png'
                    ]
                ]
            ]
        ]);

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['player_id'], 'Players'));

        return $rules;
    }
}
