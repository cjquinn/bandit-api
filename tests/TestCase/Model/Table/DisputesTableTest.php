<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class DisputesTableTest extends TestCase
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

        $this->Disputes = TableRegistry::get('Disputes');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Disputes);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testCloseResolved()
    {
        // Resolved dispute
        $dispute = $this->Disputes->get(3);

        $this->Disputes->patchEntityEdit($dispute, [
            'is_resolved' => true
        ]);

        $this->Disputes->close($dispute);

        // Scores on result should be updated
        $result = $this->Disputes->Results->get($dispute->result_id);

        $this->assertEquals(2, $result->player_a_score);
        $this->assertEquals(1, $result->player_b_score);
    }

    /**
     * @return void
     */
    public function testCloseNotResolved()
    {
        // Resolved dispute
        $dispute = $this->Disputes->get(3);

        $this->Disputes->patchEntityEdit($dispute, [
            'is_resolved' => false
        ]);

        $this->Disputes->close($dispute);

        // Result should be deleted
        $result = $this->Disputes->Results->get($dispute->result_id, [
            'contain' => [
                'PlayerAs.Users',
                'PlayerBs.Users'
            ]
        ]);

        $this->assertTrue($result->is_deleted);

        // Users rep should be -10 each
        $this->assertEquals(-10, $result->player_a->user->reputation);
        $this->assertEquals(-10, $result->player_b->user->reputation);
    }

    /**
     * @return void
     */
    public function testCloseTimeExpired()
    {
        // Resolved dispute
        $dispute = $this->Disputes->get(1);

        $this->Disputes->patchEntityEdit($dispute, [
            'is_resolved' => true // Doesn't matter the value!
        ]);

        $this->Disputes->close($dispute);

        // Result should be deleted
        $result = $this->Disputes->Results->get($dispute->result_id, [
            'contain' => [
                'PlayerAs.Users',
                'PlayerBs.Users'
            ]
        ]);

        $this->assertTrue($result->is_deleted);

        // Users rep should be -10 each
        $this->assertEquals(-8, $result->player_a->user->reputation);
        $this->assertEquals(3, $result->player_b->user->reputation);

        $this->markTestIncomplete();
    }
}
