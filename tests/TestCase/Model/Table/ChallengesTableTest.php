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

        $this->Challenges->accept($challenge, $playerId);

        $this->assertTrue($this->Challenges->exists(['id' => $challenge->id, 'player_b_id' => $playerId]));

        // Should send email to player a saying that the challenge has been accepted
    }

    /**
     * @return void
     */
    public function testPatchEntityAdd()
    {
        // Test validation
        // Required
        // Empty
        // Invalid match_datetime

        // Test method
        // club_id set
        // player_a_id set
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
        // Shouldn't include ones where match_datetime has passed
        // Find by open
        // Find by accepted
    }

    /**
     * @return void
     */
    public function testFindByPlayerId()
    {
        // should include ones where match_datetime has passed
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
