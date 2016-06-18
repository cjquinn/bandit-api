<?php

namespace App\Test\TestCase\Controller\Api;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class ResultsControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/1/results.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testUnassigned()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(9);

        $this->get('/api/clubs/1/results.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidLosingPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/results.json', [
            'losing_player_id' => 1
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddUnassignedLosingPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/results.json', [
            'losing_player_id' => 9
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

        $this->post('/api/clubs/1/results.json', [
            'losing_player_id' => 1
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/api/clubs/1/results.json', [
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
    public function testDeleteInvalidPlayer()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();

        $this->delete('/api/clubs/1/results/3.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteExistingDispute()
    {
        $results = TableRegistry::get('Results');
        
        $result = $results->get(2);
        $result->set('submitted', new Time('today'));

        $results->save($result);

        $this->_setAuthSession(3);
        $this->_setAjaxRequest();

        $this->delete('/api/clubs/1/results/2.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteTimeExpired()
    {
        $this->_setAuthSession(2);
        $this->_setAjaxRequest();

        $this->delete('/api/clubs/1/results/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_setAuthSession(2);
        $this->_setAjaxRequest();

        $this->delete('/api/clubs/1/results/3.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/api/clubs/1/results.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testViewGet()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/api/clubs/1/results/1.json');

        $this->assertResponseCode(200);
    }
}
