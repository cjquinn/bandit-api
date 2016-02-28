<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class PlayersTableTest extends TestCase
{

    public $fixtures = [
        'app.histories',
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
    public function testUpdateRatings()
    {
        $christy = $this->Players->get(1);
        $this->assertEquals(1168, $christy->daily_rating);
        $this->assertEquals(1154, $christy->rating);

        $russell = $this->Players->get(2);
        $this->assertEquals(1216, $russell->daily_rating);
        $this->assertEquals(1230, $russell->rating);

        $tom = $this->Players->get(3);
        $this->assertEquals(1216, $tom->daily_rating);
        $this->assertEquals(1216, $tom->rating);

        // +14 -14
        $this->Players->updateRatings($christy, $russell);

        $this->assertEquals(1140, $christy->rating);
        $this->assertEquals(1244, $russell->rating);

        // +16 -16
        $this->Players->updateRatings($russell, $tom);

        $this->assertEquals(1228, $russell->rating);
        $this->assertEquals(1232, $tom->rating);
    }
}
