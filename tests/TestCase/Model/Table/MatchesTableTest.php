<?php

namespace App\Test\TestCase\Model\Table;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class MatchesTableTest extends TestCase
{

    public $fixtures = [
        'app.clubs',
        'app.players',
        'app.matches',
        'app.snapshots',
        'app.users'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Matches = TableRegistry::get('Matches');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Matches);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testPatchEntityAdd()
    {
        // Required
        $match = $this->Matches->newEntity();
        $data = [];
        $clubId = 1;
        $playerId = 1;

        $this->Matches->patchEntityAdd($match, $data, $clubId, $playerId);

        $expected = [
            'player_b_id' => [
                '_required' => 'This field is required'
            ],
            'player_a_score' => [
                '_required' => 'This field is required'
            ],
            'player_b_score' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $match->getErrors());

        // Empty
        $match = $this->Matches->newEntity();
        $data = [
            'player_b_id' => '',
            'player_a_score' => '',
            'player_b_score' => ''
        ];
        $clubId = 1;
        $playerId = 1;

        $this->Matches->patchEntityAdd($match, $data, $clubId, $playerId);

        $expected = [
            'player_b_id' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'player_a_score' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'player_b_score' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $match->getErrors());

        // Invalid player b
        $match = $this->Matches->newEntity();
        $data = [
            'player_b_id' => 1,
            'player_a_score' => 3,
            'player_b_score' => 0
        ];
        $clubId = 1;
        $playerId = 1;

        $this->Matches->patchEntityAdd($match, $data, $clubId, $playerId);

        $expected = [
            'player_b_id' => [
                'invalid' => 'You cannot add matches against yourself'
            ]
        ];

        $this->assertEquals($expected, $match->getErrors());

        // Unassigned player b
        $match = $this->Matches->newEntity();
        $data = [
            'player_b_id' => 8,
            'player_a_score' => 3,
            'player_b_score' => 0
        ];
        $clubId = 1;
        $playerId = 1;

        $this->Matches->patchEntityAdd($match, $data, $clubId, $playerId);

        $expected = [
            'player_b_id' => [
                'invalid' => 'You can only add matches against members of this club'
            ]
        ];

        $this->assertEquals($expected, $match->getErrors());

        // Valid
        $match = $this->Matches->newEntity();
        $data = [
            'player_b_id' => 2,
            'player_a_score' => 3,
            'player_b_score' => 0
        ];
        $clubId = 1;
        $playerId = 1;

        $this->Matches->patchEntityAdd($match, $data, $clubId, $playerId);

        $this->assertEmpty($match->getErrors());
    }

    /**
     * @return void
     */
    public function testBeforeSaveSnapshots()
    {
        $match = $this->Matches->newEntity();
        $data = [
            'player_b_id' => 2,
            'player_a_score' => 3,
            'player_b_score' => 0
        ];
        $clubId = 1;
        $playerId = 1;

        $this->Matches->patchEntityAdd($match, $data, $clubId, $playerId);

        // Snapshots should be set
        $this->assertTrue($this->Matches->save($match) !== false);
        $this->assertNotNull($match->player_a_snapshot);
        $this->assertNotNull($match->player_b_snapshot);
    }

    /**
     * @return void
     */
    public function testBeforeSaveDeleted()
    {
        $match = $this->Matches->get(1);
        $match->set('deleted', new Time());

        $playerASnapshot = $match->player_a_snapshot;
        $playerBSnapshot = $match->player_b_snapshot;

        $this->Matches->save($match);

        $this->assertEquals($playerASnapshot, $match->player_a_snapshot);
        $this->assertEquals($playerBSnapshot, $match->player_b_snapshot);
    }

    /**
     * @return void
     */
    public function testFindWithBreakdowns()
    {
        $match = $this->Matches
            ->findById(7)
            ->find('withBreakdowns')
            ->first();

        $playerABreakdown = [
            'win' => 18,
            'loss' => -22
        ];

        $playerBBreakdown = [
            'win' => 22,
            'loss' => -18
        ];

        $this->assertEquals($playerABreakdown, $match->player_a_breakdown);
        $this->assertEquals($playerBBreakdown, $match->player_b_breakdown);
    }

    /**
     * @return void
     */
    public function testFindTree()
    {
        $match = $this->Matches->get(2);

        $query = $this->Matches->find('tree', [
            'match' => $match
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
        $match = $this->Matches->get(2);
        $matchTree = $this->Matches->idTree($match);

        $expected = [
            2 => 2,
            3 => 3,
            7 => 7,
            5 => 5
        ];
        $this->assertEquals($expected, $matchTree);

        // Matches up the tree is deleted
        $this->Matches->updateAll(['deleted' => new Time()], ['id' => 5]);
        $matchTree = $this->Matches->idTree($match);

        $expected = [
            2 => 2,
            3 => 3,
            7 => 7
        ];
        $this->assertEquals($expected, $matchTree);
    }

    /**
     * @return void
     */
    public function testSoftDelete()
    {
        $matchToDelete = $this->Matches->get(2);
        $this->Matches->softDelete($matchToDelete);

        // Match is deleted
        $this->assertNotNull($matchToDelete->deleted);

        // Matches up the tree have been amended
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

        foreach ($expected as $matchId => $snapShots) {
            $match = $this->Matches->get($matchId, ['finder' => 'populated']);

            $this->assertEquals($snapShots['a'], $match->player_a_snapshot->stats);
            $this->assertEquals($snapShots['b'], $match->player_b_snapshot->stats);
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
            $player = $this->Matches->Clubs->Players->get($playerId);

            $this->assertEquals($stats['rating'], $player->rating);
            $this->assertEquals($stats['wins'], $player->wins);
            $this->assertEquals($stats['losses'], $player->losses);
        }

        // Both users reputation is reduced by one
        $playerA = $this->Matches->PlayerAs->get($matchToDelete->player_a_id, [
            'contain' => ['Users']
        ]);

        $this->assertEquals(2, $playerA->user->reputation);

        $playerB = $this->Matches->PlayerBs->get($matchToDelete->player_b_id, [
            'contain' => ['Users']
        ]);

        $this->assertEquals(3, $playerB->user->reputation);
    }
}
