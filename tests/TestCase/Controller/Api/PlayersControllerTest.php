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
    public function testUnassigned()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(8);

        $this->post('/api/clubs/1/players.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testNonFounder()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

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

        $this->post('/api/clubs/1/players.json', []);

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
            'user' => [
                'email' => 'christyjquinn@gmail.com'
            ]
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testEditFounder()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->patch('/api/clubs/1/players/1.json', []);

        $this->assertResponseCode(403);
    }


    /**
     * @return void
     */
    public function testEditInvalidPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->patch('/api/clubs/1/players/8.json', []);

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testEditPatch()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->patch('/api/clubs/1/players/2.json', []);

        $this->assertResponseCode(200);
    }
}
