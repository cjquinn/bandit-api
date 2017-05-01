<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class DisputesTableTest extends TestCase
{

    public $fixtures = [];

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
    // public function testAfterSaveResolved()
    // {
    //     $dispute = $this->Disputes->get(2);

    //     $dispute->set('is_resolved', true);

    //     $this->Disputes->save($dispute);

    //     $losingPlayer = $this->Disputes->Results->Players->get($dispute->result->losing_player_id);
    //     $winningPlayer = $this->Disputes->Results->Players->get($dispute->result->winning_player_id);

    //     $this->assertEquals(2, $losingPlayer->reputation);
    //     $this->assertEquals(0, $winningPlayer->reputation);
    // }

    /**
     * @return void
     */
    // public function testAfterSaveUnresolved()
    // {
    //     $dispute = $this->Disputes->get(2);

    //     $dispute->set('is_resolved', false);

    //     $this->Disputes->save($dispute);

    //     $losingPlayer = $this->Disputes->Results->Players->get($dispute->result->losing_player_id);
    //     $winningPlayer = $this->Disputes->Results->Players->get($dispute->result->winning_player_id);

    //     $this->assertEquals(-8, $losingPlayer->reputation);
    //     $this->assertEquals(-10, $winningPlayer->reputation);
    // }

    /**
     * @return void
     */
    // public function testAfterSaveTimeExpired()
    // {
    //     $result = $this->Disputes->Results->get(2);

    //     $result->set('submitted', new DateTime('49 hours ago'));

    //     $this->Disputes->Results->save($result, [
    //         'ignoreEvents' => true
    //     ]);

    //     $dispute = $this->Disputes->get(2);

    //     $dispute->set('is_resolved', true);

    //     $this->Disputes->save($dispute);

    //     $losingPlayer = $this->Disputes->Results->Players->get($dispute->result->losing_player_id);
    //     $winningPlayer = $this->Disputes->Results->Players->get($dispute->result->winning_player_id);

    //     $this->assertEquals(2, $losingPlayer->reputation);
    //     $this->assertEquals(-10, $winningPlayer->reputation);
    // }
}
