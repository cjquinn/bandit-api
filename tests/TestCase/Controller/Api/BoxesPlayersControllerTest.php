<?php

namespace App\Test\TestCase\Controller\Api;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class BoxesPlayersControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/players/1.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testNonFounder()
    {
        $this->_resetRunningCycle();
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/players/1.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testRunningCycle()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/players/1.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testInvalidPlayerId()
    {
        $this->_resetRunningCycle();
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/players/9.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidClubId()
    {
        $this->_resetRunningCycle();
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/2/boxes/1/players/1.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidBoxId()
    {
        $this->_resetRunningCycle();
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/3/players/1.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_resetRunningCycle();
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/players/1.json', []);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_resetRunningCycle();
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->delete('/api/clubs/1/box-league-cycles/1/boxes/1/players/1.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testEditPut()
    {
        $this->_resetRunningCycle();
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->put('/api/clubs/1/box-league-cycles/1/boxes/1/players/1.json', []);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    private function _resetRunningCycle()
    {
        $boxLeagueCycles = TableRegistry::get('BoxLeagueCycles');

        $boxLeagueCycle = $boxLeagueCycles->get(1);

        $boxLeagueCycle->set('start', null);
        $boxLeagueCycle->set('end', null);

        $boxLeagueCycles->save($boxLeagueCycle);
    }
}
