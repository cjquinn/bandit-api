<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class BoxLeagueCyclesTableTest extends TestCase
{

    public $fixtures = [
        'app.box_league_cycles',
        'app.boxes',
        'app.clubs'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->BoxLeagueCycles = TableRegistry::get('BoxLeagueCycles');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->BoxLeagueCycles);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testNewBoxLeagueCycles()
    {
        $boxLeagueCycle = $this->BoxLeagueCycles->newEntity();

        $boxLeagueCycle->set('club_id', 1);

        $this->BoxLeagueCycles->save($boxLeagueCycle);

        $this->assertEquals(2, count($boxLeagueCycle->boxes));
    }
}
