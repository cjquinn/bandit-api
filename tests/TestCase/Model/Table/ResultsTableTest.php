<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ResultsTableTest extends TestCase
{

    public $fixtures = [
        'app.clubs',
        'app.players',
        'app.results',
        'app.users'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Results = TableRegistry::get('Results');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Results);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testBeforeSaveInvalidPlayerB()
    {
        $result = $this->Results->newEntity();

        $this->Results->patchEntity($result, [
            // Can't add a result against yourself
            'player_b_id' => 1,
            'player_a_score' => 3,
            'player_b_score' => 0
        ]);

        $result->set('club_id', 1);
        $result->set('player_a_id', 1);

        $this->assertFalse($this->Results->save($result));

        $expected = [
            '_error' => [
                'invalid' => 'You cannot add results against yourself'
            ]
        ];
        $this->assertEquals($expected, $result->errors());
    }

    /**
     * @return void
     */
    public function testBeforeSaveUnassignedPlayerB()
    {
        $result = $this->Results->newEntity();

        $this->Results->patchEntity($result, [
            'player_b_id' => 8,
            'player_a_score' => 3,
            'player_b_score' => 0
        ]);

        $result->set('club_id', 1);
        $result->set('player_a_id', 1);

        $this->assertFalse($this->Results->save($result));

        $expected = [
            '_error' => [
                'invalid' => 'You can only add results against members of this club'
            ]
        ];
        $this->assertEquals($expected, $result->errors());
    }

    /**
     * @return void
     */
    public function testBeforeSaveSnapshots()
    {
        $result = $this->Results->newEntity();

        $this->Results->patchEntity($result, [
            'player_b_id' => 1,
            'player_a_score' => 3,
            'player_b_score' => 0
        ]);

        $result->set('club_id', 1);
        $result->set('player_a_id', 2);

        // Snapshots should be set
        $this->assertTrue($this->Results->save($result) !== false);
        $this->assertTrue(is_array($result->player_a_snapshot));
        $this->assertTrue(is_array($result->player_b_snapshot));
    }

    /**
     * @return void
     */
    public function testBeforeSaveDeleted()
    {
        $result = $this->Results->get(1);
        $result->set('is_deleted', true);

        $playerASnapshot = $result->player_a_snapshot;
        $playerBSnapshot = $result->player_b_snapshot;

        $this->Results->save($result);

        $this->assertEquals($playerASnapshot, $result->player_a_snapshot);
        $this->assertEquals($playerBSnapshot, $result->player_b_snapshot);
    }

    /**
     * @return void
     */
    public function testFindTree()
    {
        $result = $this->Results->get(2);

        $query = $this->Results->find('tree', [
            'result' => $result
        ]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);

        $expected = [2, 3, 5, 7];
        $this->assertEquals($expected, $query->extract('id')->toArray());
    }

    /**
     * @return void
     */
    public function testIdTree()
    {
        $result = $this->Results->get(2);
        $resultTree = $this->Results->idTree($result);

        $expected = [
            2 => 2,
            3 => 3,
            7 => 7,
            5 => 5
        ];
        $this->assertEquals($expected, $resultTree);

        // Result up the tree is deleted
        $this->Results->updateAll(['is_deleted' => true], ['id' => 5]);
        $resultTree = $this->Results->idTree($result);

        $expected = [
            2 => 2,
            3 => 3,
            7 => 7
        ];
        $this->assertEquals($expected, $resultTree);
    }

    /**
     * @return void
     */
    public function testSoftDelete()
    {
        $resultToDelete = $this->Results->get(2);
        $this->Results->softDelete($resultToDelete);

        // Result is deleted
        $this->assertTrue($resultToDelete->is_deleted);

        // Results up the tree have been amended
        $expected = [
            3 => [
                'a' => [
                    'rating' => 1201,
                    'difference' => 21,
                    'wins' => 1,
                    'losses' => 1
                ],
                'b' => [
                    'rating' => 1179,
                    'difference' => -21,
                    'wins' => 0,
                    'losses' => 1
                ]
            ],
            5 => [
                'a' => [
                    'rating' => 1202,
                    'difference' => 22,
                    'wins' => 1,
                    'losses' => 1
                ],
                'b' => [
                    'rating' => 1198,
                    'difference' => -22,
                    'wins' => 1,
                    'losses' => 1
                ]
            ],
            7 => [
                'a' => [
                    'rating' => 1239,
                    'difference' => 19,
                    'wins' => 2,
                    'losses' => 0
                ],
                'b' => [
                    'rating' => 1182,
                    'difference' => -19,
                    'wins' => 1,
                    'losses' => 2
                ]
            ]
        ];

        foreach ($expected as $resultId => $snapShots) {
            $result = $this->Results->get($resultId);

            $this->assertEquals($snapShots['a'], $result->player_a_snapshot);
            $this->assertEquals($snapShots['b'], $result->player_b_snapshot);
        }

        // Players stats have been amended
        $expected = [
            1 => [
                'rating' => 1198,
                'wins' => 1,
                'losses' => 1
            ],
            2 => [
                'rating' => 1182,
                'wins' => 1,
                'losses' => 2
            ],
            3 => [
                'rating' => 1179,
                'wins' => 0,
                'losses' => 1
            ],
            4 => [
                'rating' => 1239,
                'wins' => 2,
                'losses' => 0
            ],
            5 => [
                'rating' => 1202,
                'wins' => 1,
                'losses' => 1
            ]
        ];

        foreach ($expected as $playerId => $stats) {
            $player = $this->Results->Clubs->Players->get($playerId);

            $this->assertEquals($stats['rating'], $player->rating);
            $this->assertEquals($stats['wins'], $player->wins);
            $this->assertEquals($stats['losses'], $player->losses);
        }

        // Both users reputation is reduced by one
        $playerA = $this->Results->PlayerAs->get($resultToDelete->player_a_id, [
            'contain' => ['Users']
        ]);

        $this->assertEquals(2, $playerA->user->reputation);

        $playerB = $this->Results->PlayerBs->get($resultToDelete->player_b_id, [
            'contain' => ['Users']
        ]);

        $this->assertEquals(3, $playerB->user->reputation);
    }
}
