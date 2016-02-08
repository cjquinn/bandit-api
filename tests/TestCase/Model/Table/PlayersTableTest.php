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
        $losingPlayer = $this->Players->get(1);
        $winningPlayer = $this->Players->get(2);

        $losingPlayer->set('rating', 1200);
        $winningPlayer->set('rating', 1200);

        $this->Players->updateRatings($losingPlayer, $winningPlayer);

        $this->assertEquals(1184, $losingPlayer->rating);
        $this->assertEquals(1216, $winningPlayer->rating);
    }
}
