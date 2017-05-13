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
        'app.results',
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

        // Scores on result should be updated
        $result = $this->Disputes->Results->get($dispute->result_id);

        $this->assertEquals(3, $result->player_a_score);
        $this->assertEquals(1, $result->player_b_score);

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

        $this->assertEquals($expected['a'], $result->player_a_snapshot);
        $this->assertEquals($expected['b'], $result->player_b_snapshot);

        // One result up the tree!
        $result = $this->Disputes->Results->get(8);

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

        $this->assertEquals($expected['a'], $result->player_a_snapshot);
        $this->assertEquals($expected['b'], $result->player_b_snapshot);

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
            $player = $this->Disputes->Results->PlayerAs->get($playerId);

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

        // Result should be deleted
        $result = $this->Disputes->Results->get($dispute->result_id, [
            'contain' => [
                'PlayerAs.Users',
                'PlayerBs.Users'
            ]
        ]);

        $this->assertTrue($result->is_deleted);

        // Users rep should be -10 each
        $this->assertEquals(-9, $result->player_a->user->reputation);
        $this->assertEquals(-9, $result->player_b->user->reputation);
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

        // Result should be deleted
        $result = $this->Disputes->Results->get($dispute->result_id, [
            'contain' => [
                'PlayerAs.Users',
                'PlayerBs.Users'
            ]
        ]);

        $this->assertTrue($result->is_deleted);

        // Users rep should be -10 each
        $this->assertEquals(-8, $result->player_a->user->reputation);
        $this->assertEquals(3, $result->player_b->user->reputation);
    }
}
