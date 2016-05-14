<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

class BoxesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'BoxLeagues'
            ],
            'belongsToMany' => [
                'Players'
            ]
        ]);
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['box_league_id'], 'BoxLeagues'));

        return $rules;
    }
}
