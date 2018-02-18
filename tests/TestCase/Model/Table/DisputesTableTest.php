<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class DisputesTableTest extends TestCase
{

    public $fixtures = [
        'app.clubs',
        'app.disputes',
        'app.players',
        'app.matches',
        'app.users'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Disputes = TableRegistry::get('Disputes');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Disputes);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testCloseResolved()
    {
        // Resolved dispute
        $dispute = $this->Disputes->get(3);

        $this->Disputes->patchEntityEdit($dispute, [
            'is_resolved' => true
        ]);

        $this->Disputes->close($dispute);

        $this->assertTrue($this->Disputes->exists(['id' => 3, 'is_resolved' => true]));

        // Scores on match should be updated
        $match = $this->Disputes->Matches->get($dispute->match_id);

        $this->assertEquals(3, $match->player_a_score);
        $this->assertEquals(1, $match->player_b_score);

        $expected = [
            'a' => [
                'rating' => 1240,
                'difference' => 40,
                'wins' => 3,
                'losses' => 1
            ],
            'b' => [
                'rating' => 1160,
                'difference' => -40,
                'wins' => 1,
                'losses' => 3
            ]
        ];

        $this->assertEquals($expected['a'], $match->player_a_snapshot);
        $this->assertEquals($expected['b'], $match->player_b_snapshot);

        // One match up the tree!
        $match = $this->Disputes->Matches->get(8);

        $expected = [
            'a' => [
                'rating' => 1255,
                'difference' => 15,
                'wins' => 4,
                'losses' => 1
            ],
            'b' => [
                'rating' => 1145,
                'difference' => -15,
                'wins' => 1,
                'losses' => 4
            ]
        ];

        $this->assertEquals($expected['a'], $match->player_a_snapshot);
        $this->assertEquals($expected['b'], $match->player_b_snapshot);

        // Players updated
        $expected = [
            6 => [
                'rating' => 1255,
                'wins' => 4,
                'losses' => 1
            ],
            7 => [
                'rating' => 1145,
                'wins' => 1,
                'losses' => 4
            ]
        ];

        foreach ($expected as $playerId => $stats) {
            $player = $this->Disputes->Matches->PlayerAs->get($playerId);

            $this->assertEquals($stats['rating'], $player->rating);
            $this->assertEquals($stats['wins'], $player->wins);
            $this->assertEquals($stats['losses'], $player->losses);
        }
    }

    /**
     * @return void
     */
    public function testCloseNotResolved()
    {
        // Resolved dispute
        $dispute = $this->Disputes->get(3);

        $this->Disputes->patchEntityEdit($dispute, [
            'is_resolved' => false
        ]);

        $this->Disputes->close($dispute);

        $this->assertTrue($this->Disputes->exists(['id' => 3, 'is_resolved' => false]));

        // Matches should be deleted
        $match = $this->Disputes->Matches->get($dispute->match_id, [
            'contain' => [
                'PlayerAs.Users',
                'PlayerBs.Users'
            ],
            'ignoreBeforeFind' => true
        ]);

        $this->assertNotNull($match->deleted);

        // Users rep should be -10 each
        $this->assertEquals(-9, $match->player_a->user->reputation);
        $this->assertEquals(-9, $match->player_b->user->reputation);
    }

    /**
     * @return void
     */
    public function testCloseTimeExpired()
    {
        // Resolved dispute
        $dispute = $this->Disputes->get(1);

        $this->Disputes->patchEntityEdit($dispute, [
            'is_resolved' => true // Doesn't matter the value!
        ]);

        $this->Disputes->close($dispute);

        $this->assertTrue($this->Disputes->exists(['id' => 1, 'is_resolved' => false]));

        // Matches should be deleted
        $match = $this->Disputes->Matches->get($dispute->match_id, [
            'contain' => [
                'PlayerAs.Users',
                'PlayerBs.Users'
            ],
            'ignoreBeforeFind' => true
        ]);

        $this->assertNotNull($match->deleted);

        // Users rep should be -10 each
        $this->assertEquals(-8, $match->player_a->user->reputation);
        $this->assertEquals(3, $match->player_b->user->reputation);
    }
}
