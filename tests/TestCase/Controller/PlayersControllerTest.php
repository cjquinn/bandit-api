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
    public function testAddUnauthenticated()
    {
        $this->post('/clubs/1/players.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->post('/clubs/1/players.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAuthSession(1);

        $this->post('/clubs/1/players.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
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
    public function testIndexUnauthenticated()
    {
        $this->get('/clubs/1/players.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testIndexInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/players.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/players.json');

        $this->assertResponseCode(200);
        $this->assertEquals(7, $this->viewVariable('players')->count());
    }

    /**
     * @return void
     */
    public function testViewUnauthenticated()
    {
        $this->get('/clubs/1/players/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/players/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewInvalidMatchId()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/2/players/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/players/1.json');

        $this->assertResponseCode(200);
    }
}
