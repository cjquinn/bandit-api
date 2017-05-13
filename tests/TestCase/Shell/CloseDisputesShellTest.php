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
        $this->Disputes = TableRegistry::get('Disputes');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->CloseDisputes);
        unset($this->Disputes);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testMain()
    {
        $this->CloseDisputes->main();

        $dispute = $this->Disputes->get(1);

        $this->assertFalse($dispute->is_resolved);
    }
}
