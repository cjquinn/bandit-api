<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class DisputesTableTest extends TestCase
{

    public $fixtures = [];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Disputes = TableRegistry::get('Disputes');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Disputes);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testTest()
    {
        $this->markTestIncomplete();
    }
}
