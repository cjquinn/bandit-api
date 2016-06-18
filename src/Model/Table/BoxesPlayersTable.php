<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

class BoxesPlayersTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->primaryKey(['box_league_cycle_id', 'player_id']);

        $this->addAssociations([
            'belongsTo' => [
                'Boxes',
                'BoxLeagueCycles',
                'Players'
            ]
        ]);
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['box_id'], 'Boxes'));
        $rules->add($rules->existsIn(['box_league_cycle_id'], 'BoxLeagueCycles'));
        $rules->add($rules->existsIn(['player_id'], 'Players'));

        return $rules;
    }
}
