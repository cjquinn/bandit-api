<?php

namespace App\Test\TestCase\Controller;

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
        $this->_testUnauthorised([
            'post' => '/clubs/1/players.json',
            'get' => '/clubs/1/players.json',
            'get' => '/clubs/1/players/1.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAuthorised()
    {
        $this->_testAuthorised([
            // Unassigned
            'get' => '/clubs/2/players.json',
            // Non founder
            'post' => '/clubs/2/players.json',
            // Invalid player
            'get' => '/clubs/1/players/8.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/clubs/1/players.json', []);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/clubs/1/players.json', [
            'user' => [
                'email' => 'christyjquinn@gmail.com'
            ]
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIndex()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/clubs/1/players.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testView()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/clubs/1/players/1.json');

        $this->assertResponseCode(200);
    }
}
