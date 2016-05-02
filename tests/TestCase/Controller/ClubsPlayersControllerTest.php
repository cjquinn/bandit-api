<?php

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

class ClubsPlayersControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testAddUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/2/players/2.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddUnassigned()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/api/clubs/2/players/2.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/2/players/2.json', []);

        $this->assertResponseCode(200);
    }
}
