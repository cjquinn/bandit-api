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
        'app.clubs_players',
        'app.disputes',
        'app.histories',
        'app.logins',
        'app.players',
        'app.results'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->io = $this->getMock('Cake\Console\ConsoleIo');
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
        $results = TableRegistry::get('results');

        $result = $results->get(2);
        $result->set('submitted', new Time('49 hours ago'));

        $results->save($result, [
            'ignoreEvents' => true
        ]);

        $this->CloseDisputes->main();

        $this->assertFalse($results->exists([
            'id' => 2
        ]));
        $this->assertFalse($results->Disputes->exists([
            'result_id' => 2
        ]));
    }
}
