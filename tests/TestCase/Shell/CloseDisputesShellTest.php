<?php
namespace App\Test\TestCase\Shell;

use App\Shell\CloseDisputesShell;
use Cake\TestSuite\TestCase;

/**
 * App\Shell\CloseDisputesShell Test Case
 */
class CloseDisputesShellTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->io = $this->getMock('Cake\Console\ConsoleIo');
        $this->CloseDisputes = new CloseDisputesShell($this->io);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CloseDisputes);

        parent::tearDown();
    }

    /**
     * Test main method
     *
     * @return void
     */
    public function testMain()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
