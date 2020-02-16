<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class DisputesTableTest extends TestCase
{

    public $fixtures = [
        'app.Clubs',
        'app.Disputes',
        'app.Players',
        'app.Matches',
        'app.Snapshots',
        'app.Users'
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
        $matchId = 6;

        // Resolved dispute
        $dispute = $this->Disputes->get($matchId);

        $this->Disputes->patchEntityEdit($dispute, [
            'is_resolved' => true
        ]);

        $this->Disputes->close($dispute);

        $this->assertTrue($this->Disputes->exists(['match_id' => $matchId, 'is_resolved' => true]));

        // Scores on match should be updated
        $match = $this->Disputes->Matches->get($dispute->match_id, [
            'finder' => 'populated'
        ]);

        $this->assertEquals(3, $match->player_a_score);
        $this->assertEquals(1, $match->player_b_score);

        $expected = [
            'player_a_snapshot' => [
                'rating' => 1240,
                'difference' => 40,
                'wins' => 3,
                'losses' => 1
            ],
            'player_b_snapshot' => [
                'rating' => 1160,
                'difference' => -40,
                'wins' => 1,
                'losses' => 3
            ]
        ];

        $this->assertEquals($expected['player_a_snapshot'], $match->player_a_snapshot->stats);
        $this->assertEquals($expected['player_b_snapshot'], $match->player_b_snapshot->stats);

        // One match up the tree!
        $match = $this->Disputes->Matches->get(8, [
            'finder' => 'populated'
        ]);

        $expected = [
            'player_a_snapshot' => [
                'rating' => 1255,
                'difference' => 15,
                'wins' => 4,
                'losses' => 1
            ],
            'player_b_snapshot' => [
                'rating' => 1145,
                'difference' => -15,
                'wins' => 1,
                'losses' => 4
            ]
        ];

        $this->assertEquals($expected['player_a_snapshot'], $match->player_a_snapshot->stats);
        $this->assertEquals($expected['player_b_snapshot'], $match->player_b_snapshot->stats);

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
        $matchId = 6;

        // Resolved dispute
        $dispute = $this->Disputes->get($matchId);

        $this->Disputes->patchEntityEdit($dispute, [
            'is_resolved' => false
        ]);

        $this->Disputes->close($dispute);

        $this->assertTrue($this->Disputes->exists(['match_id' => $matchId, 'is_resolved' => false]));

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
        $matchId = 2;

        // Resolved dispute
        $dispute = $this->Disputes->get($matchId);

        $this->Disputes->patchEntityEdit($dispute, [
            'is_resolved' => true // Doesn't matter the value!
        ]);

        $this->Disputes->close($dispute);

        $this->assertTrue($this->Disputes->exists(['match_id' => $matchId, 'is_resolved' => false]));

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

    /**
     * @return void
     */
    public function testFindByClubId()
    {
        // club_id = 1 - Disputes - 2, 5
        $query = $this->Disputes->find('byClubId', ['clubId' => 1]);

        $expected = [2, 5, 6];

        $this->assertEquals($expected, $query->extract('match_id')->toArray());

        // club_id = 2 - Disputes - 10
        $query = $this->Disputes->find('byClubId', ['clubId' => 2]);

        $expected = [10];

        $this->assertEquals($expected, $query->extract('match_id')->toArray());
    }

    /**
     * @return void
     */
    public function testFindByUserId()
    {
        // user_id = 1 - Disputes - 2, 5, 10
        $query = $this->Disputes->find('byUserId', ['userId' => 1]);

        $expected = [2, 5, 10];

        $this->assertEquals($expected, $query->extract('match_id')->toArray());

        // user_id = 2 - Disputes - 2
        $query = $this->Disputes->find('byUserId', ['userId' => 2]);

        $expected = [2];

        $this->assertEquals($expected, $query->extract('match_id')->toArray());

        // user_id = 5 - Disputes - 5
        $query = $this->Disputes->find('byUserId', ['userId' => 5]);

        $expected = [5];

        $this->assertEquals($expected, $query->extract('match_id')->toArray());
    }
}
