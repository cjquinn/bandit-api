<?php

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RacketwareSessionsTable;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class RacketwareSessionsTableTest extends TestCase
{
    /**
     * @var \App\Model\Table\RacketwareSessionsTable
     */
    public $RacketwareSessions;

    /**
     * @var array
     */
    public $fixtures = [
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $config = TableRegistry::getTableLocator()->exists('RacketwareSessions') ? [] : ['className' => RacketwareSessionsTable::class];
        $this->RacketwareSessions = TableRegistry::getTableLocator()->get('RacketwareSessions', $config);
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->RacketwareSessions);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testValidationAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
