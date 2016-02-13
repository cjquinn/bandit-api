<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class PlayersTableTest extends TestCase
{

    public $fixtures = [
        'app.players'
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
        $playerA = $this->Players->get(1);
        $playerB = $this->Players->get(1);

        $playerA->set('rating', 1200);
        $playerB->set('rating', 1200);

        $this->Players->updateRatings($playerA, $playerB);

        $this->assertEquals(1184, $playerA->rating);
        $this->assertEquals(1216, $playerB->rating);

        $this->Players->updateRatings($playerA, $playerB);

        $this->assertEquals(1169, $playerA->rating);
        $this->assertEquals(1231, $playerB->rating);

        $this->Players->updateRatings($playerB, $playerA);

        $this->assertEquals(1188, $playerA->rating);
        $this->assertEquals(1212, $playerB->rating);
    }
}
