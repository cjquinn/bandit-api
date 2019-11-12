<?php

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChallengesTable;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ChallengesTableTest extends TestCase
{

    /**
     * @var \App\Model\Table\ChallengesTable
     */
    public $Challenges;

    /**
     * @var array
     */
    public $fixtures = [
        'app.challenges',
        'app.clubs',
        'app.matches',
        'app.players',
        'app.users'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Challenges = TableRegistry::get('Challenges');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Challenges);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testAccept()
    {
        // Existing player_b_id
        $playerId = 2;
        $challenge = $this->Challenges->get(1);
        $challenge->player_b_id = 4;

        $this->assertFalse($this->Challenges->accept($challenge, $playerId));

        // Existing player_a_id is playerID
        $playerId = 1;
        $challenge = $this->Challenges->get(1);

        $this->assertFalse($this->Challenges->accept($challenge, $playerId));

        // In the past
        $playerId = 2;
        $challenge = $this->Challenges->get(1);
        $challenge->match_datetime = new Time(strtotime('-5 hour'));

        $this->assertFalse($this->Challenges->accept($challenge, $playerId));

        // valid
        $playerId = 2;
        $challenge = $this->Challenges->get(1);

        $challengesTableMock = $this->getMockForModel(
            'App\Model\Table\ChallengesTable',
            ['getMailer'],
            ['alias' => 'ChallengesTable', 'table' => 'challenges']
        );

        $emailMock = $this->getMockBuilder('Cake\Mailer\Email')
            ->setMethods(['send'])
            ->getMock();

        $mailerMock = $this->getMockBuilder('App\Mailer\ChallengeMailer')
            ->setConstructorArgs([$emailMock])
            ->setMethods(['playerAccepted'])
            ->getMock();

        $mailerMock
            ->expects($this->once())
            ->method('playerAccepted');

        $challengesTableMock
            ->expects($this->once())
            ->method('getMailer')
            ->will($this->returnValue($mailerMock));

        $this->assertTrue($challengesTableMock->accept($challenge, $playerId));
        $this->assertTrue($this->Challenges->exists(['id' => $challenge->id, 'player_b_id' => $playerId]));
    }

    /**
     * @return void
     */
    public function testPatchEntityAdd()
    {
        // Test validation
        // Required
        $challenge = $this->Challenges->newEntity();
        $data = [];
        $clubId = 1;
        $playerId = 1;

        $this->Challenges->patchEntityAdd($challenge, $data, $clubId, $playerId);

        $expected = [
            'location' => [
                '_required' => 'This field is required'
            ],
            'match_datetime' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $challenge->getErrors());

        // Empty
        $challenge = $this->Challenges->newEntity();
        $data = [
            'location' => '',
            'match_datetime' => ''
        ];
        $clubId = 1;
        $playerId = 1;

        $this->Challenges->patchEntityAdd($challenge, $data, $clubId, $playerId);

        $expected = [
            'location' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'match_datetime' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $challenge->getErrors());

        // Invalid match_datetime
        $challenge = $this->Challenges->newEntity();
        $data = [
            'location' => 'Somewhere',
            'match_datetime' => date('Y-m-d H:i:s', strtotime('-1 hour'))
        ];
        $clubId = 1;
        $playerId = 1;

        $this->Challenges->patchEntityAdd($challenge, $data, $clubId, $playerId);

        $expected = [
            'match_datetime' => [
                'invalid' => 'The date and time must be in the future'
            ]
        ];

        $this->assertEquals($expected, $challenge->getErrors());

        // Test method
        $challenge = $this->Challenges->newEntity();
        $data = [
            'location' => 'Somewhere',
            'match_datetime' => date('Y-m-d H:i:s', strtotime('+1 hour'))
        ];
        $clubId = 1;
        $playerId = 1;

        $this->Challenges->patchEntityAdd($challenge, $data, $clubId, $playerId);

        // No errors
        $this->assertEmpty($challenge->getErrors());

        // club_id set
        $this->assertEquals($clubId, $challenge->club_id);

        // player_a_id set
        $this->assertEquals($playerId, $challenge->player_a_id);
    }

    /**
     * @return void
     */
    public function testSoftDelete()
    {
        // Invalid player a id
        $challenge = $this->Challenges->get(3);
        $playerId = 1;

        $this->assertFalse($this->Challenges->softDelete($challenge, $playerId));

        // Valid
        $challenge = $this->Challenges->get(3);
        $playerId = 2;

        $challengesTableMock = $this->getMockForModel(
            'App\Model\Table\ChallengesTable',
            ['getMailer'],
            ['alias' => 'ChallengesTable', 'table' => 'challenges']
        );

        $emailMock = $this->getMockBuilder('Cake\Mailer\Email')
            ->setMethods(['send'])
            ->getMock();

        $mailerMock = $this->getMockBuilder('App\Mailer\ChallengeMailer')
            ->setConstructorArgs([$emailMock])
            ->setMethods(['playerDeleted'])
            ->getMock();

        $mailerMock
            ->expects($this->once())
            ->method('playerDeleted');

        $challengesTableMock
            ->expects($this->once())
            ->method('getMailer')
            ->will($this->returnValue($mailerMock));

        // If match was accepted send email to player b
        $this->assertTrue($challengesTableMock->softDelete($challenge, $playerId));

        // Check deleted is set
        $this->assertTrue($this->Challenges->exists(['id' => $challenge->id, 'deleted IS NOT' => null]));

        // If time is less than 24 hours -10 rep to player a
        $playerA = $this->Challenges->PlayerAs->get($challenge->player_a_id, ['contain' => 'Users']);

        $this->assertEquals(-6, $playerA->user->reputation);

        // Check rep isn't decreased and no email is sent
        $challenge = $this->Challenges->get(1);
        $playerId = 1;

        $challengesTableMock = $this->getMockForModel(
            'App\Model\Table\ChallengesTable',
            ['getMailer'],
            ['alias' => 'ChallengesTable', 'table' => 'challenges']
        );

        $emailMock = $this->getMockBuilder('Cake\Mailer\Email')
            ->setMethods(['send'])
            ->getMock();

        $mailerMock = $this->getMockBuilder('App\Mailer\ChallengeMailer')
            ->setConstructorArgs([$emailMock])
            ->setMethods(['playerDeleted'])
            ->getMock();

        $mailerMock
            ->expects($this->never())
            ->method('playerDeleted');

        $challengesTableMock
            ->expects($this->never())
            ->method('getMailer')
            ->will($this->returnValue($mailerMock));

        $this->assertTrue($challengesTableMock->softDelete($challenge, $playerId));

        $playerA = $this->Challenges->PlayerAs->get($challenge->player_a_id, ['contain' => 'Users']);

        $this->assertEquals(3, $playerA->user->reputation);

        // Check rep isn't decreased when accepted and more than 24 hours
        $challenge = $this->Challenges->get(7);
        $playerId = 2;

        $challengesTableMock = $this->getMockForModel(
            'App\Model\Table\ChallengesTable',
            ['getMailer'],
            ['alias' => 'ChallengesTable', 'table' => 'challenges']
        );

        $emailMock = $this->getMockBuilder('Cake\Mailer\Email')
            ->setMethods(['send'])
            ->getMock();

        $mailerMock = $this->getMockBuilder('App\Mailer\ChallengeMailer')
            ->setConstructorArgs([$emailMock])
            ->setMethods(['playerDeleted'])
            ->getMock();

        $mailerMock
            ->expects($this->once())
            ->method('playerDeleted');

        $challengesTableMock
            ->expects($this->once())
            ->method('getMailer')
            ->will($this->returnValue($mailerMock));

        // If match was accepted send email to player b
        $this->assertTrue($challengesTableMock->softDelete($challenge, $playerId));

        // If time is less than 24 hours -10 rep to player a
        $playerA = $this->Challenges->PlayerAs->get($challenge->player_a_id, ['contain' => 'Users']);

        $this->assertEquals(-6, $playerA->user->reputation);
    }

    /**
     * @return void
     */
    public function testFindByPlayerId()
    {
        // Where player_a_id OR player_b_id matches passed playerId
        $query = $this->Challenges->find('byPlayerId', ['player_id' => 2]);

        $this->assertEquals(2, $query->count());
        $this->assertEquals([3, 7], $query->extract('id')->toArray());

        // All case
        $query = $this->Challenges->find('byPlayerId', ['player_id' => 'all']);

        $this->assertEquals(3, $query->count());
        $this->assertEquals([2, 1, 7], $query->extract('id')->toArray());
    }

    /**
     * @return void
     */
    public function testFindFiltered()
    {
        // Find by all
        $query = $this->Challenges->find('filtered', ['filter' => 'all']);

        $this->assertEquals(4, $query->count());
        $this->assertEquals([3, 2, 1, 7], $query->extract('id')->toArray());

        // Find by open
        $query = $this->Challenges->find('filtered', ['filter' => 'open']);

        $this->assertEquals(2, $query->count());
        $this->assertEquals([2, 1], $query->extract('id')->toArray());

        // Find by accepted
        $query = $this->Challenges->find('filtered', ['filter' => 'accepted']);

        $this->assertEquals(2, $query->count());
        $this->assertEquals([3, 7], $query->extract('id')->toArray());
    }

    /**
     * @return void
     */
    public function testReport()
    {
        // Invalid player
        $challenge = $this->Challenges->get(3);
        $playerId = 1;

        $this->assertFalse($this->Challenges->report($challenge, $playerId));

        // Valid
        $challenge = $this->Challenges->get(3);
        $playerId = 2;

        $this->assertTrue($this->Challenges->report($challenge, $playerId));

        $this->assertTrue($this->Challenges->exists(['id' => $challenge->id, 'deleted IS NOT' => null]));

        $playerB = $this->Challenges->PlayerAs->get($challenge->player_b_id, ['contain' => 'Users']);

        $this->assertEquals(-9, $playerB->user->reputation);
    }

    /**
     * @return void
     */
    public function testWithdraw()
    {
        // Invalid
        $playerId = 2;
        $challenge = $this->Challenges->get(3);

        $this->assertFalse($this->Challenges->withdraw($challenge, $playerId));

        // Valid
        $playerId = 3;
        $challenge = $this->Challenges->get(3);

        // Should send email to player a saying player b has withdrawn
        $challengesTableMock = $this->getMockForModel(
            'App\Model\Table\ChallengesTable',
            ['getMailer'],
            ['alias' => 'ChallengesTable', 'table' => 'challenges']
        );

        $emailMock = $this->getMockBuilder('Cake\Mailer\Email')
            ->setMethods(['send'])
            ->getMock();

        $mailerMock = $this->getMockBuilder('App\Mailer\ChallengeMailer')
            ->setConstructorArgs([$emailMock])
            ->setMethods(['playerWithdrew'])
            ->getMock();

        $mailerMock
            ->expects($this->once())
            ->method('playerWithdrew');

        $challengesTableMock
            ->expects($this->once())
            ->method('getMailer')
            ->will($this->returnValue($mailerMock));

        $this->assertTrue($challengesTableMock->withdraw($challenge, $playerId));

        // Ensure player b is removed
        $this->assertTrue($this->Challenges->exists(['id' => $challenge->id, 'player_b_id IS' => null]));

        // If time is less than 24 hours negative rep to player b
        $playerB = $this->Challenges->PlayerAs->get($playerId, ['contain' => 'Users']);

        $this->assertEquals(-9, $playerB->user->reputation);

        // If time is more than 24 hours no negative rep to player b
        $playerId = 1;
        $challenge = $this->Challenges->get(7);

        $this->assertTrue($this->Challenges->withdraw($challenge, $playerId));

        $playerB = $this->Challenges->PlayerAs->get($playerId, ['contain' => 'Users']);

        $this->assertEquals(3, $playerB->user->reputation);
    }
}
