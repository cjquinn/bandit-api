<?php

namespace App\Test\TestCase\Command;

use App\Command\SendWeeklyDigestCommand;
use App\Model\Entity\Club;

use Cake\Console\Command;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class SendWeeklyDigestCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * @var array
     */
    public $fixtures = [
        'app.Challenges',
        'app.Clubs',
        'app.Disputes',
        'app.Players',
        'app.Matches',
        'app.Snapshots',
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
    public function testExecuteNewClub()
    {
        // Create a club
        $club = $this->createClub();

        // Test digest
        $this->exec('send_weekly_digest');

        $this->assertExitCode(Command::CODE_SUCCESS);
        $this->assertOutputContains('Squelch: 7 weekly digest emails sent');
        $this->assertOutputContains('Ping Pong Game On: 2 weekly digest emails sent');
        $this->assertOutputContains('Test Club: 1 weekly digest emails sent');
    }

    /**
     * @return void
     */
    public function testExecuteNoActivity()
    {
        // Create a club
        $club = $this->createClub();

        // Set player created date to be in past
        $playersTable = TableRegistry::get('Players');

        $playersTable->updateAll(
            ['created' => date('Y-m-d H:i:s', strtotime('two weeks ago'))],
            ['club_id' => $club->id]
        );

        // Test no digest
        $this->exec('send_weekly_digest');

        $this->assertExitCode(Command::CODE_SUCCESS);
        $this->assertOutputContains('Squelch: 7 weekly digest emails sent');
        $this->assertOutputContains('Ping Pong Game On: 2 weekly digest emails sent');
        $this->assertOutputContains('Test Club: 0 weekly digest emails sent');
    }

    /**
     * @return void
     */
    public function testExecuteNoActivityNoWeeklyDigestPreference()
    {
        // Create a club
        $club = $this->createClub();

        // Set user preferences
        TableRegistry::get('Users')->updateAll(
            [
                'email_preferences' => [
                    'challenge_created' => true,
                    'match_added' => true,
                    'weekly_digest' => false
                ]
            ],
            ['1 = 1']
        );

        // Test no digest
        $this->exec('send_weekly_digest');

        $this->assertExitCode(Command::CODE_SUCCESS);
        $this->assertOutputContains('Squelch: 0 weekly digest emails sent');
        $this->assertOutputContains('Ping Pong Game On: 0 weekly digest emails sent');
        $this->assertOutputContains('Test Club: 0 weekly digest emails sent');
    }

    /**
     * @return void
     */
    public function testExecuteWithMatches()
    {
        // Create a club
        $club = $this->createClub();

        // Add some players and set their join date as being past last week
        $player = $this->createPlayer($club);

        TableRegistry::get('Players')->updateAll(
            ['created' => date('Y-m-d H:i:s', strtotime('two weeks ago'))],
            ['club_id' => $club->id]
        );

        // Add some matches and set their date as being past last week
        $matchesTable = TableRegistry::get('Matches');

        $match = $matchesTable->newEntity();
        $matchesTable->patchEntityAdd(
            $match,
            [
                'player_b_id' => $player->id,
                'player_a_score' => 1,
                'player_b_score' => 1
            ],
            $club->id,
            $club->founder->players[2]->id
        );
        $matchesTable->save($match);

        $matchesTable->updateAll(
            ['created' => date('Y-m-d H:i:s', strtotime('two weeks ago'))],
            ['club_id' => $club->id]
        );

        // Add some matches for this week
        $match = $matchesTable->newEntity();
        $matchesTable->patchEntityAdd(
            $match,
            [
                'player_b_id' => $player->id,
                'player_a_score' => 1,
                'player_b_score' => 1
            ],
            $club->id,
            $club->founder->players[2]->id
        );
        $matchesTable->save($match);

        // Test that weekly digest is queued for weekly leaderboard
        $this->exec('send_weekly_digest');

        $this->assertExitCode(Command::CODE_SUCCESS);
        $this->assertOutputContains('Squelch: 7 weekly digest emails sent');
        $this->assertOutputContains('Ping Pong Game On: 2 weekly digest emails sent');
        $this->assertOutputContains('Test Club: 2 weekly digest emails sent');
    }

    /**
     * @return void
     */
    public function testExecuteWithOpenChallenges()
    {
        // Create a club
        $club = $this->createClub();

        // Add some open challenges
        $challengesTable = TableRegistry::get('Challenges');

        $challenge = $challengesTable->newEntity();
        $challengesTable->patchEntityAdd(
            $challenge,
            [
                'location' => 'Here',
                'match_datetime' => Time('+7 days')
            ],
            $club->id,
            $club->founder->players[2]->id
        );
        $challengesTable->save($challenge);

        // Test that weeky digest is queued for open challenges
        $this->exec('send_weekly_digest');

        $this->assertExitCode(Command::CODE_SUCCESS);
        $this->assertOutputContains('Squelch: 7 weekly digest emails sent');
        $this->assertOutputContains('Ping Pong Game On: 2 weekly digest emails sent');
        $this->assertOutputContains('Test Club: 1 weekly digest emails sent');
    }

    /**
     * @return void
     */
    public function testExecuteWithUpcomingChallenges()
    {
        // Create a club
        $club = $this->createClub();

        // Create a player
        $player = $this->createPlayer($club);

        // Add some open challenges
        $challengesTable = TableRegistry::get('Challenges');

        $challenge = $challengesTable->newEntity();
        $challengesTable->patchEntityAdd(
            $challenge,
            [
                'location' => 'Here',
                'match_datetime' => Time('+7 days')
            ],
            $club->id,
            $club->founder->players[2]->id
        );
        $challenge->set('player_b_id', $player->id);
        $challengesTable->save($challenge);

        // Test that weeky digest is queued for open challenges
        $this->exec('send_weekly_digest');

        $this->assertExitCode(Command::CODE_SUCCESS);
        $this->assertOutputContains('Squelch: 7 weekly digest emails sent');
        $this->assertOutputContains('Ping Pong Game On: 2 weekly digest emails sent');
        $this->assertOutputContains('Test Club: 2 weekly digest emails sent');
    }

    /**
     * @return void
     */
    public function testExecuteWithNewPlayers()
    {
        // Create a club
        $this->createPlayer($this->createClub());

        // Test no digest
        $this->exec('send_weekly_digest');

        $this->assertExitCode(Command::CODE_SUCCESS);
        $this->assertOutputContains('Squelch: 7 weekly digest emails sent');
        $this->assertOutputContains('Ping Pong Game On: 2 weekly digest emails sent');
        $this->assertOutputContains('Test Club: 2 weekly digest emails sent');
    }

    /**
     * @return \App\Model\Entity\Club
     */
    private function createClub()
    {
        $clubsTable = TableRegistry::get('Clubs');

        $club = $clubsTable->newEntity();
        $clubsTable->patchEntityAdd(
            $club,
            ['name' => 'Test Club'],
            ['id' => 1]
        );

        return $clubsTable->save($club);
    }

    /**
     * @return \App\Model\Entity\Player
     */
    private function createPlayer(Club $club)
    {
        $playersTable = TableRegistry::get('Players');

        $player = $playersTable->newEntity();
        $playersTable->patchEntityAdd(
            $player,
            ['user' => ['email' => 'russell@banditmatch.com']],
            $club->id,
            $club->founder->players[2]->id
        );

        return $playersTable->save($player);
    }
}
