<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

use DateTime;

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
        $date = new DateTime('today');

        $christy = $this->Players->get(1);
        $russell = $this->Players->get(2);
        $tom = $this->Players->get(3);

        // +14 -14
        $this->Players->updateRatings($christy, $russell, $date);

        $this->assertEquals(1140, $christy->rating);
        $this->assertEquals(1244, $russell->rating);

        // +16 -16
        $this->Players->updateRatings($russell, $tom, $date);

        $this->assertEquals(1228, $russell->rating);
        $this->assertEquals(1232, $tom->rating);
    }
}
