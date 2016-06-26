<?php

namespace App\Model\Table;

use ArrayObject;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
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
            ],
            'hasMany' => [
                'LosingPlayerResults' => [
                    'className' => 'Results',
                    'bindingKey' => [
                        'box_id',
                        'winning_player_id',
                        'losing_player_id'
                    ],
                    'foreignKey' => [
                        'box_id',
                        'losing_player_id',
                        'winning_player_id'
                    ]
                ],
                'WinningPlayerResults' => [
                    'className' => 'Results',
                    'foreignKey' => $this->primaryKey()
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

        $valid = function ($value, $context) {
            return isset($context['data']['losses']) && isset($context['data']['wins']) && $context['data']['losses'] <= $context['data']['wins'];
        };

        $validator
            ->requirePresence('losses', 'create')
            ->add('losses', [
                'nonNegativeInteger' => [
                    'rule' => ['naturalNumber', true],
                    'last' => true
                ],
                'lessThanOrEqual' => [
                    'rule' => ['comparison', '<=', 2],
                ],
                'valid' => [
                    'rule' => $valid,
                    'message' => 'You must enter more wins then losses'
                ]
            ]);

        $validator
            ->requirePresence('wins', 'create')
            ->add('wins', [
                'nonNegativeInteger' => [
                    'rule' => ['naturalNumber', true],
                    'last' => true
                ],
                'greaterThanOrEqual' => [
                    'rule' => ['comparison', '>=', 1],
                ],
                'lessThanOrEqual' => [
                    'rule' => ['comparison', '<=', 3],
                ],
                'valid' => [
                    'rule' => $valid,
                    'message' => 'You must enter more wins then losses'
                ]
            ]);

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

    /**
     * @return void
     */
    public function beforeSave(Event $event, EntityInterface $boxMatch, ArrayObject $options)
    {
        if ($boxMatch->isNew()) {
            $club = $this->Players->Clubs
                ->find()
                ->innerJoinWith('BoxLeagueCycles.Boxes', function ($q) use ($boxMatch) {
                    $q->where([
                        'Boxes.id' => $boxMatch->box_id
                    ]);

                    return $q;
                })
                ->firstOrFail();

            $losingPlayerResults = [];
            for ($i = 0; $i < $boxMatch->losses; $i++) {
                $losingPlayerResult = $this->LosingPlayerResults->newEntity();
                $losingPlayerResult->set([
                    'box_id' => $boxMatch->box_id,
                    'club_id' => $club->id,
                    'losing_player_id' => $boxMatch->winning_player_id,
                    'winning_player_id' => $boxMatch->losing_player_id
                ], ['guard' => false]);

                array_push($losingPlayerResults, $losingPlayerResult);
            }
            $boxMatch->set('losing_player_results', $losingPlayerResults);

            $winningPlayerResults = [];
            for ($i = 0; $i < $boxMatch->wins; $i++) {
                $winningPlayerResult = $this->WinningPlayerResults->newEntity();
                $winningPlayerResult->set([
                    'box_id' => $boxMatch->box_id,
                    'club_id' => $club->id,
                    'losing_player_id' => $boxMatch->losing_player_id,
                    'winning_player_id' => $boxMatch->winning_player_id
                ], ['guard' => false]);

                array_push($winningPlayerResults, $winningPlayerResult);
            }
            $boxMatch->set('winning_player_results', $winningPlayerResults);
        }
    }

    /**
     * @return int
     */
    public function losingPlayerScore(array $score)
    {
        return $score['losses'] + 1;
    }

    /**
     * @return int
     */
    public function winningPlayerScore(array $score)
    {
        if ($score['wins'] < 3) {
            return $score['wins'] + 1;
        }

        return 6 - $score['losses'];
    }
}
