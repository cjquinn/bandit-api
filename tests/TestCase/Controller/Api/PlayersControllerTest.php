<?php

namespace App\Test\TestCase\Controller\Api;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class PlayersControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/1/players.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddUnassigned()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(9);

        $this->post('/api/clubs/1/players.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/players.json', [
            'name' => '',
            'login' => [
                'email' => ''
            ]
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/players.json', [
            'name' => 'Nathan Rathbone',
            'login' => [
                'email' => 'nathan@bandit.localhost'
            ]
        ]);

        $this->assertResponseCode(200);

        $this->assertTrue(TableRegistry::get('ClubsPlayers')->exists([
            'club_id' => 1,
            'player_id' => json_decode($this->_response->body(), true)['player']['id']
        ]));
    }

    /**
     * @return void
     */
    public function testEditInvalidPlayerId()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->put('/api/players/2.json', [
            'name' => 'Christy J Quinn'
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testEditBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->put('/api/players/1.json', [
            'name' => ''
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testEditPut()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->put('/api/players/1.json', [
            'name' => 'Christy J Quinn'
        ]);

        $this->assertResponseCode(200);
    }
}
