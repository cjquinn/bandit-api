<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ResultsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Logins',
                'Losers' => [
                    'className' => 'Players',
                    'foreignKey' => 'loser_id'
                ],
                'Winners' => [
                    'className' => 'Players',
                    'foreignKey' => 'winner_id'
                ]
            ],
            'hasMany' => [
                'LosersHistories' => [
                    'className' => 'Histories',
                    'foreignKey' => [
                        'loser_id',
                        'result_id'
                    ]
                ],
                'WinnersHistories' => [
                    'className' => 'Histories',
                    'foreignKey' => [
                        'winner_id',
                        'result_id'
                    ]
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
            ->requirePresence('loser_id', 'create')
            ->notEmpty('loser_id');

        $validator
            ->requirePresence('date', 'create')
            ->notEmpty('date')
            ->add('date', 'valid', [
                'rule' => 'datetime'
            ]);

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['loser_id'], 'Losers'));
        $rules->add($rules->existsIn(['winner_id'], 'Winners'));

        return $rules;
    }

    /**
     * @param \App\Model\Entity\Player $losingPlayer The losing player
     * @param \App\Model\Entity\Player $winningPlayer The winning player
     * @return float
     */
    public function expectedScore(Player $losingPlayer, Player $winningPlayer)
    {
        return 1 / (1 + (pow(10, ($losingPlayer->rating - $winningPlayer->rating) / 400)));
    }

    /**
     * @param \App\Model\Entity\Player $player The player
     * @param float $score (0 | 0.5 | 1)
     * @param float $expectedScore The players expected score
     * @return float
     */
    public function updateRating(Player $player, $score, $expectedScore)
    {
        return $player->rating + (32 * ($score - $expectedScore));
    }
}
