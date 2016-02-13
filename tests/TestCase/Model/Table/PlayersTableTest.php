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

        $russell = $this->Players->get(2);
        $this->assertEquals(1216, $russell->daily_rating);

        $tom = $this->Players->get(3);
        $this->assertEquals(1200, $tom->daily_rating);

        // + 15 - 15
        $this->Players->updateRatings($christy, $russell);

        $this->assertEquals(1154, $christy->rating);
        $this->assertEquals(1246, $russell->rating);

        // + 15 - 15
        $this->Players->updateRatings($christy, $tom);

        $this->assertEquals(1139, $christy->rating);
        $this->assertEquals(1215, $tom->rating);
    }
}
