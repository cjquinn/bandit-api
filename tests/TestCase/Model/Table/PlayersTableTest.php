<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

use DateTime;

class PlayersTableTest extends TestCase
{

    public $fixtures = [
        'app.clubs',
        'app.clubs_players',
        'app.histories',
        'app.logins',
        'app.players',
        'app.results'
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
    public function testRevert()
    {
        $history = $this->Players->Histories->get([
            'player_id' => 1,
            'result_id' => 1
        ]);

        $this->Players->revert($history);

        $club = $this->Players->Club
            ->find()
            ->where([
                'club_id' => 1,
                'player_id' => 1
            ])
            ->firstOrFail();

        $expected = [
            'club_id' => 1,
            'player_id' => 1,
            'losses' => 0,
            'rating' => 1200,
            'wins' => 0
        ];
        $this->assertEquals($expected, $club->toArray());
    }

    /**
     * @return void
     */
    public function testUpdateRating()
    {
        $club = $this->Players->Club->get([
            'player_id' => 1,
            'club_id' => 1
        ]);

        // 1154 + round(32 * (1 - 0))
        $this->Players->updateRating($club, 0, 1);

        $this->assertEquals(1186, $club->rating);

        $club->losses = 15;
        $club->wins = 16;

        // 1186 + round(24 * (1 - 0))
        $this->Players->updateRating($club, 0, 1);

        $this->assertEquals(1210, $club->rating);
    }

    /**
     * @return void
     */
    public function testUpdateRatings()
    {
        $date = new DateTime('today');

        $christy = $this->Players
            ->findById(1)
            ->find('club', [
                'clubId' => 1
            ])
            ->firstOrFail();
        $russell = $this->Players
            ->findById(2)
            ->find('club', [
                'clubId' => 1
            ])
            ->firstOrFail();
        $tom = $this->Players
            ->findById(3)
            ->find('club', [
                'clubId' => 1
            ])
            ->firstOrFail();

        // +14 -14
        $this->Players->updateRatings($christy, $russell, 1, $date);

        $this->assertEquals(1140, $christy->club->rating);
        $this->assertEquals(1244, $russell->club->rating);

        $this->Players->save($christy);
        $this->Players->save($russell);

        // +16 -16
        $this->Players->updateRatings($russell, $tom, 1, $date);

        $this->assertEquals(1228, $russell->club->rating);
        $this->assertEquals(1232, $tom->club->rating);
    }
}
