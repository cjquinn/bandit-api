<?php

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChallengesTable;

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
        'app.challenges'
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
            ->setMethods(['playerAccepted'])
            ->getMock();

        $mailerMock
            ->expects($this->once())
            ->method('playerAccepted');

        $challengesTableMock
            ->expects($this->once())
            ->method('getMailer')
            ->will($this->returnValue($mailerMock));

        $challengesTableMock->accept($challenge, $playerId);

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
                'invalid' => 'The match date & time must be in the future.'
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
        // Check deleted is set

        // If match was accepted send email to player b

            // If time is less than 24 hours negative rep to player a
    }

    /**
     * @return void
     */
    public function testFindFiltered()
    {
        // All shouldn't include ones where match_id is not null

        // Should include ones where match_datetime has passed
        // Find by all
        // Shouldn't include ones where match_datetime has passed
        // Find by open
        // Find by accepted
    }

    /**
     * @return void
     */
    public function testFindByPlayerId()
    {
        // Where player_a_id OR player_b_id matches passed playerId
        // Add all case
    }

    /**
     * @return void
     */
    public function testWithdraw()
    {
        // Ensure player b is removed

        // Should send email to player a saying player b has withdrawn

        // If time is less than 24 hours negative rep to player b
    }
}
