<?php

namespace App\Test\TestCase\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class PlayersTableTest extends TestCase
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

        $this->Players = TableRegistry::get('Players');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Players);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testPatchEntityAdd()
    {
        // Bad data
        $player = $this->Players->newEntity();

        $player->set('club_id', 1);

        $this->Players->patchEntityAdd($player, []);

        $expected = [
            'user' => [
                '_required' => 'This field is required'
            ]
        ];
        $this->assertEquals($expected, $player->errors());

        // New user
        $player = $this->Players->newEntity();

        $player->set('club_id', 1);

        $this->Players->patchEntityAdd($player, [
            'user' => [
                'email' => 'some@new.player'
            ]
        ]);

        $this->assertNotNull($player->user);
        $this->assertEquals('some@new.player', $player->user->email);

        // Existing user non member
        $player = $this->Players->newEntity();

        $player->set('club_id', 1);

        $this->Players->patchEntityAdd($player, [
            'user' => [
                'email' => 'gareth@bandit.play'
            ]
        ]);

        $this->assertNull($player->user);
        $this->assertEquals(8, $player->user_id);

        // Existing user member
        $player = $this->Players->newEntity();

        $player->set('club_id', 1);

        $this->Players->patchEntityAdd($player, [
            'user' => [
                'email' => 'christy@bandit.play'
            ]
        ]);

        $expected = [
            'user' => [
                'email' => [
                    'duplicate' => 'A member of this club already exists with that email'
                ]
            ]
        ];
        $this->assertEquals($expected, $player->errors());
    }

    /**
     * @return void
     */
    public function testBeforeSave()
    {
        $player = $this->Players->newEntity();

        $player->set('club_id', 1);
        $player->set('user_id', 1);

        $this->Players->save($player);

        $this->assertEquals($player->rating, Configure::read('Bandit.initialRating'));
    }

    /**
     * @return void
     */
    public function testExpectedScores()
    {
        $expectedScores = $this->Players->expectedScores(1200, 1200);

        $this->assertTrue(is_array($expectedScores));

        $expected = [
            'a' => 0.5,
            'b' => 0.5
        ];
        $this->assertEquals($expected, $expectedScores);

        $expectedScores = $this->Players->expectedScores(1216, 1184);

        $expected = [
            'a' => 0.5459219228,
            'b' => 0.4540780772
        ];
        $this->assertEquals($expected, $expectedScores);

        $expectedScores = $this->Players->expectedScores(1215, 1185);

        $expected = [
            'a' => 0.543066492,
            'b' => 0.456933508
        ];
        $this->assertEquals($expected, $expectedScores);
    }

    /**
     * @return void
     */
    public function testRatingChange()
    {
        // Expected scores
        $expectedScores = $this->Players->expectedScores(1200, 1200);

        // Player A wins
        $ratingChange = $this->Players->ratingChange($expectedScores['a'], 1, 40);

        $expected = 20;
        $this->assertEquals($expected, $ratingChange);

        // Player B loses
        $ratingChange = $this->Players->ratingChange($expectedScores['b'], 0, 40);

        $expected = -20;
        $this->assertEquals($expected, $ratingChange);

        // Expected scores
        $expectedScores = $this->Players->expectedScores(1216, 1184);

        // Player A draws
        $ratingChange = $this->Players->ratingChange($expectedScores['a'], 0.5, 40);

        $expected = -2;
        $this->assertEquals($expected, $ratingChange);

        // Player B draws
        $ratingChange = $this->Players->ratingChange($expectedScores['b'], 0.5, 40);

        $expected = 2;
        $this->assertEquals($expected, $ratingChange);

        // Expected scores
        $expectedScores = $this->Players->expectedScores(1215, 1185);

        // Player A loses
        $ratingChange = $this->Players->ratingChange($expectedScores['a'], 0, 40);

        $expected = -22;
        $this->assertEquals($expected, $ratingChange);

        // Player B wins
        $ratingChange = $this->Players->ratingChange($expectedScores['b'], 1, 40);

        $expected = 22;
        $this->assertEquals($expected, $ratingChange);
    }

    /**
     * @return void
     */
    public function testRevert()
    {
        $result = $this->Players->Clubs->Results->get(1);

        $this->assertTrue($this->Players->revert($result, 'player_a'));

        $playerA = $this->Players->get($result->player_a_id);

        $this->assertEquals(1200, $playerA->rating);
        $this->assertEquals(0, $playerA->wins);
        $this->assertEquals(0, $playerA->losses);

        $this->assertTrue($this->Players->revert($result, 'player_b'));

        $playerB = $this->Players->get($result->player_b_id);

        $this->assertEquals(1200, $playerB->rating);
        $this->assertEquals(0, $playerB->wins);
        $this->assertEquals(0, $playerB->losses);

        // Deleted result
        $result->set('is_deleted', true);

        $this->assertTrue($this->Players->revert($result, 'player_a'));

        $playerA = $this->Players->get($result->player_a_id, [
            'contain' => ['Users']
        ]);

        $this->assertEquals(2, $playerA->user->reputation);
    }

    /**
     * @return void
     */
    public function testSnapshot()
    {
        $player = $this->Players->get(1);

        $snapshot = $this->Players->snapshot($player, 0.5, 4, 2);

        $this->assertEquals(1255, $player->rating);
        $this->assertEquals(6, $player->wins);
        $this->assertEquals(3, $player->losses);

        $expected = [
            'rating' => 1255,
            'difference' => 40,
            'wins' => 6,
            'losses' => 3
        ];
        $this->assertEquals($expected, $snapshot);
    }

    /**
     * @return void
     */
    public function testSnapshots()
    {
        $result = $this->Players->Clubs->Results->newEntity([
            'player_b_id' => 7,
            'player_a_score' => 1,
            'player_b_score' => 0
        ]);

        $result->set('club_id', 1);
        $result->set('player_a_id', 6);

        $snapshots = $this->Players->snapshots($result);

        $expected = [
            'a' => [
                'rating' => 1256,
                'difference' => 18,
                'wins' => 3,
                'losses' => 0
            ],
            'b' => [
                'rating' => 1144,
                'difference' => -18,
                'wins' => 0,
                'losses' => 3
            ]
        ];
        $this->assertEquals($expected, $snapshots);

        // Reputation updated
        $userA = $this->Players->Users->get(6);
        $userB = $this->Players->Users->get(7);

        $this->assertEquals(3, $userA->reputation);
        $this->assertEquals(3, $userB->reputation);
    }
}
