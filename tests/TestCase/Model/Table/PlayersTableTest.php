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
                'rating' => 1238,
                'difference' => 18,
                'wins' => 2,
                'losses' => 0
            ],
            'b' => [
                'rating' => 1162,
                'difference' => -18,
                'wins' => 0,
                'losses' => 2
            ]
        ];
        $this->assertEquals($expected, $snapshots);

        // Reputation updated
        $userA = $this->Players->Users->get(6);
        $userB = $this->Players->Users->get(7);

        $this->assertEquals(2, $userA->reputation);
        $this->assertEquals(2, $userB->reputation);
    }
}
