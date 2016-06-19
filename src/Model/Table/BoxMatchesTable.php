<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class BoxMatchesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->primaryKey(['box_id', 'losing_player_id', 'winning_player_id']);

        $this->addAssociations([
            'belongsTo' => [
                'Boxes',
                'LosingPlayers' => [
                    'className' => 'Players',
                    'foreignKey' => 'losing_player_id'
                ],
                'Players',
                'WinningPlayers' => [
                    'className' => 'Players',
                    'foreignKey' => 'winning_player_id'
                ]
            ]
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'BoxMatch.disputed' => [
                    'disputed' => 'always'
                ]
            ]
        ]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('losing_player_id', 'create')
            ->nonNegativeInteger('losing_player_id');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['box_id'], 'Boxes'));
        $rules->add($rules->existsIn(['losing_player_id'], 'Players'));
        $rules->add($rules->existsIn(['winning_player_id'], 'Players'));

        return $rules;
    }
}
