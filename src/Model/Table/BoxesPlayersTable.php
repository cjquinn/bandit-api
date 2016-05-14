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
        $this->primaryKey(['box_id', 'player_id']);

        $this->addAssociations([
            'belongsTo' => [
                'Boxes',
                'Players'
            ],
            'belongsToMany' => [
                'Results' => [
                    'foreignKey' => [
                        'boxes_player_box_id',
                        'boxes_player_player_id'
                    ]
                ]
            ]
        ]);
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['box_id'], 'Boxes'));
        $rules->add($rules->existsIn(['player_id'], 'Players'));

        return $rules;
    }
}
