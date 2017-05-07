<?php

namespace App\Test\TestCase\Shell;

use App\Shell\CloseDisputesShell;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class CloseDisputesShellTest extends TestCase
{

    public $fixtures = [
        'app.clubs',
        'app.disputes',
        'app.players',
        'app.results',
        'app.users'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->io = $this->createMock('Cake\Console\ConsoleIo');
        $this->CloseDisputes = new CloseDisputesShell($this->io);
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->CloseDisputes);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testMain()
    {
        $this->markTestIncomplete();
    }
}
