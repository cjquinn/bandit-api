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
    public function testAddUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/1/box-league-cycles.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddNonFounder()
    {
        $this->_setAjaxRequest();

        $this->_setAuthSession(2);
        $this->post('/api/clubs/1/box-league-cycles.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddExistingCycle()
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
    public function testEditUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/1/box-league-cycles/1.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testEditInvalidClubId()
    {
        $this->_setAjaxRequest();

        $this->_setAuthSession(1);
        $this->put('/api/clubs/2/box-league-cycles/1.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testEditNonFounder()
    {
        $this->_setAjaxRequest();

        $this->_setAuthSession(2);
        $this->put('/api/clubs/1/box-league-cycles/1.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testEditRunningCycle()
    {
        $this->_setAjaxRequest();

        $this->_setAuthSession(1);
        $this->put('/api/clubs/1/box-league-cycles/1.json', []);

        $this->assertResponseCode(403);
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
            'boxes' => [
                [
                    'id' => 1,
                    'players' => [
                        '_ids' => [
                            1
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertResponseCode(200);
    }
}
