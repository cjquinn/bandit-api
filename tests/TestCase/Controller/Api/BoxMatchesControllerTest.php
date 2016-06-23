<?php

namespace App\Test\TestCase\Controller\Api;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\TestSuite\IntegrationTestCase;

class BoxMatchesControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testUnassigned()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(9);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testInvalidClubId()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/2/box-league-cycles/1/boxes/1/box-matches.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testInvalidBoxId()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/3/box-matches.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidLosingPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 1,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddNonClubLosingPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 9,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddNonBoxWinningPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(5);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 1,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddNonBoxLosingPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 5,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddDuplicate()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 1,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddExistingDisputes()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(3);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 1,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }
}
