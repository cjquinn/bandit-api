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
        // 1184
        $playerA = $this->Players->get(1);
        // 1216
        $playerB = $this->Players->get(2);

        // + 15 - 15
        $this->Players->updateRatings($playerA, $playerB);

        $this->assertEquals(1169, $playerA->rating);
        $this->assertEquals(1231, $playerB->rating);

        // + 15 - 15
        $this->Players->updateRatings($playerA, $playerB);

        $this->assertEquals(1154, $playerA->rating);
        $this->assertEquals(1246, $playerB->rating);

        // + 17 - 17
        $this->Players->updateRatings($playerB, $playerA);

        $this->assertEquals(1171, $playerA->rating);
        $this->assertEquals(1229, $playerB->rating);
    }
}
