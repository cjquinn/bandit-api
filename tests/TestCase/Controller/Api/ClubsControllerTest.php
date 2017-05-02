<?php

namespace App\Test\TestCase\Controller\Api;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class ClubsControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs.json', [
            'name' => ''
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * The creation of a club when not logged in.
     * Requires the user to signup at the same time.
     *
     * @return void
     */
    public function testAddUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs.json', [
            'name' => 'Ping Pong Game On',
            'founder' => [
                'name' => 'Alex Farthing',
                'email' => 'alex@gmail.com',
                'password' => 'password'
            ]
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * The creation of a club when logged in.
     *
     * @return void
     */
    public function testAddAuthorised()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs.json', [
            'name' => 'Ping Pong Game On'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testEditUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->put('/api/clubs/1.json', [
            'name' => 'Squelch Bandit'
        ]);

        $this->assertResponseCode(401);
    }

    /**
     * @return void
     */
    public function testEditNonFounder()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->put('/api/clubs/1.json', [
            'name' => 'Squelch Bandit'
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

        $this->put('/api/clubs/1.json', [
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

        $this->put('/api/clubs/1.json', [
            'name' => 'Squelch Bandit'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIndexUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->get('/api/clubs.json');

        $this->assertResponseCode(401);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/api/clubs.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testViewUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->get('/api/clubs/1.json');

        $this->assertResponseCode(401);
    }

    /**
     * @return void
     */
    public function testViewUnassigned()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->get('/api/clubs/2.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewGet()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/api/clubs/1.json');

        $this->assertResponseCode(200);
    }
}
