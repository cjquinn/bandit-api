<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ClubsPlayersTableTest extends TestCase
{

    public $fixtures = [];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->ClubsPlayers = TableRegistry::get('ClubsPlayers');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->ClubsPlayers);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testExpectedScores()
    {
        $expectedScores = $this->ClubsPlayers->expectedScores(1200, 1200);

        $this->assertTrue(is_array($expectedScores));

        $expected = [
            'a' => 0.5,
            'b' => 0.5
        ];
        $this->assertEquals($expected, $expectedScores);

        $expectedScores = $this->ClubsPlayers->expectedScores(1216, 1184);

        $expected = [
            'a' => 0.5459219228,
            'b' => 0.4540780772
        ];
        $this->assertEquals($expected, $expectedScores);

        $expectedScores = $this->ClubsPlayers->expectedScores(1215, 1185);

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
        $expectedScores = $this->ClubsPlayers->expectedScores(1200, 1200);

        // Player A wins
        $ratingChange = $this->ClubsPlayers->ratingChange($expectedScores['a'], 1, 40);

        $expected = 20;
        $this->assertEquals($expected, $ratingChange);

        // Player B loses
        $ratingChange = $this->ClubsPlayers->ratingChange($expectedScores['b'], 0, 40);

        $expected = -20;
        $this->assertEquals($expected, $ratingChange);

        // Expected scores
        $expectedScores = $this->ClubsPlayers->expectedScores(1216, 1184);

        // Player A draws
        $ratingChange = $this->ClubsPlayers->ratingChange($expectedScores['a'], 0.5, 40);

        $expected = -2;
        $this->assertEquals($expected, $ratingChange);

        // Player B draws
        $ratingChange = $this->ClubsPlayers->ratingChange($expectedScores['b'], 0.5, 40);

        $expected = 2;
        $this->assertEquals($expected, $ratingChange);

        // Expected scores
        $expectedScores = $this->ClubsPlayers->expectedScores(1215, 1185);

        // Player A loses
        $ratingChange = $this->ClubsPlayers->ratingChange($expectedScores['a'], 0, 40);

        $expected = -22;
        $this->assertEquals($expected, $ratingChange);

        // Player B wins
        $ratingChange = $this->ClubsPlayers->ratingChange($expectedScores['b'], 1, 40);

        $expected = 22;
        $this->assertEquals($expected, $ratingChange);
    }
}
