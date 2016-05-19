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
                'BoxLeagueCycles'
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
        $rules->add($rules->existsIn(['box_league_cycle_id'], 'BoxLeagueCycles'));

        return $rules;
    }

    /**
     * Application rules
     *
     * A player cannot be in more than one box
     * There must be at least 4 players in each box
     */
}
