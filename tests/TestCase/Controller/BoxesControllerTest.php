<?php

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class BoxesControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testAddUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/1/box-league-cycles/1/boxes.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidClubId()
    {
        $this->_setAjaxRequest();

        $this->_setAuthSession(1);
        $this->post('/api/clubs/2/box-league-cycles/1/boxes.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddNonFounder()
    {
        $this->_setAjaxRequest();

        $this->_setAuthSession(2);
        $this->post('/api/clubs/1/box-league-cycles/1/boxes.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddRunningCycle()
    {
        $this->_setAjaxRequest();

        $this->_setAuthSession(1);
        $this->post('/api/clubs/1/box-league-cycles/1/boxes.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $boxLeagueCycles = TableRegistry::get('BoxLeagueCycles');

        $boxLeagueCycle = $boxLeagueCycles->get(1);

        $boxLeagueCycle->set('start', null);
        $boxLeagueCycle->set('end', null);

        $boxLeagueCycles->save($boxLeagueCycle);

        $this->_setAjaxRequest();

        $this->_setAuthSession(1);
        $this->post('/api/clubs/1/box-league-cycles/1/boxes.json', []);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testDeleteUnauthorised()
    {
    }

    /**
     * @return void
     */
    public function testDeleteNonFounder()
    {
    }

    /**
     * @return void
     */
    public function testDeleteExistingPlayers()
    {
    }

    /**
     * @return void
     */
    public function testDeleteRunningCycle()
    {
    }

    /**
     * @return void
     */
    public function testDeleteOnlyTwoBoxes()
    {
    }

    /**
     * @return void
     */
    public function testDeleteInvalidClubId()
    {
    }

    /**
     * @return void
     */
    public function testDeleteInvalidBoxLeagueCycleId()
    {
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
    }
}
