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
        $result->set('created', new Time('49 hours ago'));

        $results->save($result);

        $this->CloseDisputes->main();

        $dipute = TableRegistry::get('Disputes')->get([
            'player_id' => 3,
            'result_id' => 2,
        ], [
            'contain' => [
                'Results' => [
                    'LosingPlayers',
                    'WinningPlayers'
                ]
            ]
        ]);

        $this->assertTrue(!is_null($dipute->is_resolved) && !$dipute->is_resolved);
        $this->assertEquals(3, $dipute->result->losing_player->reputation);
        $this->assertEquals(-10, $dipute->result->winning_player->reputation);
    }
}
