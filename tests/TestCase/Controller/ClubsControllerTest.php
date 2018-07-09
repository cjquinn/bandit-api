<?php

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class ClubsControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_testUnauthorised([
            'get' => '/clubs.json',
            'put' => '/clubs/1.json',
            'get' => '/clubs/1.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAuthorised()
    {
        $this->_testAuthorised([
            'put' => '/clubs/2.json',
            'get' => '/clubs/2.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/clubs.json', [
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
        $this->post('/clubs.json', [
            'name' => 'Ping Pong Game On',
            'founder' => [
                'first_name' => 'Alex',
                'last_name' => 'Farthing',
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
        $this->post('/clubs.json', [
            'name' => 'Ping Pong Game On'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testEditBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);
        $this->put('/clubs/1.json', [
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
        $this->put('/clubs/1.json', [
            'name' => 'Squelch Bandit'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);
        $this->get('/clubs.json');

        $this->assertResponseCode(200);
        $this->assertEquals(1, $this->viewVariable('clubs')->count());
    }

    /**
     * @return void
     */
    public function testViewGet()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);
        $this->get('/clubs/1.json');

        $this->assertResponseCode(200);
    }
}
