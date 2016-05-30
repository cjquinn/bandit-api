<?php

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

use DateTime;

class BoxLeagueCyclesControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/1/box-league-cycles.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testNonFounder()
    {
        $this->_setAjaxRequest();

        $this->_setAuthSession(2);
        $this->post('/api/clubs/1/box-league-cycles.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testRunningCycle()
    {
        $this->_setAjaxRequest();

        $this->_setAuthSession(1);
        $this->post('/api/clubs/1/box-league-cycles.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $boxLeagueCycles = TableRegistry::get('BoxLeagueCycles');

        $boxLeagueCycle = $boxLeagueCycles->get(1);
        $boxLeagueCycle->set('end', new DateTime('yesterday'));

        $boxLeagueCycles->save($boxLeagueCycle);

        $this->_setAjaxRequest();

        $this->_setAuthSession(1);
        $this->post('/api/clubs/1/box-league-cycles.json', []);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testEditInvalidClubId()
    {
        $this->_setAjaxRequest();

        $this->_setAuthSession(1);
        $this->put('/api/clubs/2/box-league-cycles/1.json', [
            'start' => (new DateTime('today'))->format('Y-m-d'),
            'end' => (new DateTime('+2 weeks'))->format('Y-m-d')
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testEditBadData()
    {
        $boxLeagueCycles = TableRegistry::get('BoxLeagueCycles');

        $boxLeagueCycle = $boxLeagueCycles->get(1);

        $boxLeagueCycle->set('start', null);
        $boxLeagueCycle->set('end', null);

        $boxLeagueCycles->save($boxLeagueCycle);

        $this->_setAjaxRequest();

        $this->_setAuthSession(1);
        $this->put('/api/clubs/1/box-league-cycles/1.json', []);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testEditPut()
    {
        $boxLeagueCycles = TableRegistry::get('BoxLeagueCycles');

        $boxLeagueCycle = $boxLeagueCycles->get(1);

        $boxLeagueCycle->set('start', null);
        $boxLeagueCycle->set('end', null);

        $boxLeagueCycles->save($boxLeagueCycle);

        $this->_setAjaxRequest();

        $this->_setAuthSession(1);
        $this->put('/api/clubs/1/box-league-cycles/1.json', [
            'start' => (new DateTime('today'))->format('Y-m-d'),
            'end' => (new DateTime('+2 weeks'))->format('Y-m-d')
        ]);

        $this->assertResponseCode(200);
    }
}
