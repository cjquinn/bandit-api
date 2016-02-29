<?php

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class ResultsControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testAddUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/results.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidLoserId()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/results.json', [
            'losing_player_id' => 1
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddExistingDisputes()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(3);

        $this->post('/results.json', [
            'losing_player_id' => 1
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/results.json', [
            'losing_player_id' => null
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/results.json', [
            'losing_player_id' => 1
        ]);

        $this->assertResponseCode(200);

        $christy = TableRegistry::get('Players')->get(1);
        $russell = TableRegistry::get('Players')->get(2);

        $this->assertEquals(4, $christy->reputation);
        $this->assertEquals(3, $russell->reputation);
    }

    /**
     * @return void
     */
    public function testIndexUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->get('results.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('results.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testViewUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->get('results/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewGet()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('results/1.json');

        $this->assertResponseCode(200);
    }
}
