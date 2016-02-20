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
     * tearDown method
     *
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
        $this->assertEquals(1184, $christy->daily_rating);
        $this->assertEquals(1168, $christy->rating);

        $russell = $this->Players->get(2);
        $this->assertEquals(1216, $russell->daily_rating);
        $this->assertEquals(1231, $russell->rating);

        $tom = $this->Players->get(3);
        $this->assertEquals(1216, $tom->daily_rating);
        $this->assertEquals(1216, $tom->rating);

        // + 15 - 15
        $this->Players->updateRatings($christy, $russell);

        $this->assertEquals(1153, $christy->rating);
        $this->assertEquals(1246, $russell->rating);

        // + 15 - 15
        $this->Players->updateRatings($christy, $tom);

        $this->assertEquals(1138, $christy->rating);
        $this->assertEquals(1231, $tom->rating);
    }
}
