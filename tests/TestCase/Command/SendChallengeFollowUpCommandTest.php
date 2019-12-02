<?php

namespace App\Test\TestCase\Command;

use App\Command\SendChallengeFollowUpCommand;

use Cake\Console\Command;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class SendChallengeFollowUpCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * @var array
     */
    public $fixtures = [
        'app.Challenges',
        'app.Players',
        'app.Users'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->useCommandRunner();
    }

    /**
     * @return void
     */
    public function testExecuteWithPastChallenges()
    {
        $this->exec('send_challenge_follow_up');
        $this->assertExitCode(Command::CODE_SUCCESS);
        $this->assertOutputContains('1 challenge follow ups sent');

        $challenge = TableRegistry::get('Challenges')->get(3);
        $this->assertNotNull($challenge);
    }

    /**
     * @return void
     */
    public function testExecuteNoPastChallenges()
    {
        TableRegistry::get('Challenges')->updateAll(
            ['match_datetime' => date('Y-m-d H:i:s', strtotime('+5 hour'))],
            ['id' => 3]
        );

        $this->exec('send_challenge_follow_up');
        $this->assertExitCode(Command::CODE_SUCCESS);
        $this->assertOutputContains('0 challenge follow ups sent');
    }

    /**
     * @return void
     */
    public function testExecuteFollowUpSent()
    {
        TableRegistry::get('Challenges')->updateAll(
            ['follow_up_sent' => date('Y-m-d H:i:s')],
            ['id' => 3]
        );

        $this->exec('send_challenge_follow_up');
        $this->assertExitCode(Command::CODE_SUCCESS);
        $this->assertOutputContains('0 challenge follow ups sent');
    }
}
