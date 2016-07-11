<?php

namespace App\Model\Table;

use App\Model\Entity\BoxMatch;

use ArrayObject;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

use DateTime;

class BoxMatchesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Boxes',
                'LosingBoxesPlayers' => [
                    'bindingKey' => [
                        'box_id',
                        'player_id'
                    ],
                    'className' => 'BoxesPlayers',
                    'foreignKey' => [
                        'box_id',
                        'losing_player_id'
                    ]
                ],
                'Players',
                'WinningBoxesPlayers' => [
                    'bindingKey' => [
                        'box_id',
                        'player_id'
                    ],
                    'className' => 'BoxesPlayers',
                    'foreignKey' => [
                        'box_id',
                        'winning_player_id'
                    ]
                ]
            ],
            'hasMany' => [
                'Results'
            ]
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.BoxMatch.disputed' => [
                    'disputed' => 'always'
                ],
                'Model.beforeSave' => [
                    'submitted' => 'new'
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
            // Update losing players score
            $losingBoxesPlayer = $this->LosingBoxesPlayers
                ->find()
                ->where([
                    'box_id' => $boxMatch->box_id,
                    'player_id' => $boxMatch->losing_player_id
                ])
                ->firstOrFail();

            $losingBoxesPlayer->set('points', $this->losingPlayerPoints($boxMatch->score));
            $boxMatch->set('losing_boxes_player', $losingBoxesPlayer);

            // Update winning players score
            $winningPlayer = $this->WinningBoxesPlayers
                ->find()
                ->where([
                    'box_id' => $boxMatch->box_id,
                    'player_id' => $boxMatch->winning_player_id
                ])
                ->firstOrFail();

            $winningPlayer->set('points', $this->winningPlayerPoints($boxMatch->score));
            $boxMatch->set('winning_boxes_player', $winningPlayer);

            $club = $this->Players->Clubs
                ->find()
                ->innerJoinWith('BoxLeagueCycles.Boxes', function ($q) use ($boxMatch) {
                    $q->where([
                        'Boxes.id' => $boxMatch->box_id
                    ]);

                    return $q;
                })
                ->firstOrFail();

            $results = [];

            $createResults = function ($isWinner) use ($boxMatch, $club, &$results) {
                if ($isWinner) {
                    $count = $boxMatch->wins;
                    $losingPlayerId = $boxMatch->losing_player_id;
                    $winningPlayerId = $boxMatch->winning_player_id;
                } else {
                    $count = $boxMatch->losses;
                    $losingPlayerId = $boxMatch->winning_player_id;
                    $winningPlayerId = $boxMatch->losing_player_id;
                }

                for ($i = 0; $i < $count; $i++) {
                    $result = $this->Results->newEntity();
                    $result->set([
                        'box_id' => $boxMatch->box_id,
                        'club_id' => $club->id,
                        'losing_player_id' => $losingPlayerId,
                        'winning_player_id' => $winningPlayerId
                    ], ['guard' => false]);
                    array_push($results, $result);
                }
            };

            $createResults(true);
            $createResults(false);

            $boxMatch->set('results', $results);
        }
    }

    /**
     * @return void
     */
    public function dispute(BoxMatch $boxMatch)
    {
        $this->dispatchEvent('Model.BoxMatch.disputed', [
            'boxMatch' => $boxMatch
        ]);

        $this->save($boxMatch);
    }

    /**
     * @return bool
     */
    public function isDisputed($id)
    {
        return $this->exists([
            'id' => $id,
            'disputed IS NOT' => null
        ]);
    }

    /**
     * @return bool
     */
    public function isLosingPlayer($id, $losingPlayerId)
    {
        return $this->exists([
            'id' => $id,
            'losing_player_id' => $losingPlayerId
        ]);
    }

    /**
     * @return int
     */
    public function losingPlayerPoints(array $score)
    {
        return $score['losses'] + 1;
    }

    /**
     * @return void
     */
    public function wasWithinLast($id, $period)
    {
        return $this->exists([
            'id' => $id,
            'submitted >=' => new DateTime('-' . $period)
        ]);
    }

    /**
     * @return int
     */
    public function winningPlayerPoints(array $score)
    {
        if ($score['wins'] < 3) {
            return $score['wins'] + 1;
        }

        return 6 - $score['losses'];
    }
}
