<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class BoxesTableTest extends TestCase
{

    public $fixtures = [
        'app.boxes',
        'app.box_league_cycles'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Boxes = TableRegistry::get('Boxes');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Boxes);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testSaveNew()
    {
        $box = $this->Boxes->newEntity();

        $box->set('box_league_cycle_id', 1);

        $this->Boxes->save($box);

        $this->assertEquals($box->division, 3);
    }
}
