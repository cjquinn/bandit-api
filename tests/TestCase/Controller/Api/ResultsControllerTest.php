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
        $this->_setAuthSession(8);

        $this->get('/api/clubs/1/results.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddExistingDisputes()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/results.json', [
            'player_b_id' => 2,
            'player_a_score' => 1,
            'player_b_score' => 0
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/api/clubs/1/results.json', []);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/api/clubs/1/results.json', [
            'player_b_id' => 1,
            'player_a_score' => 1,
            'player_b_score' => 0
        ]);

        $this->assertResponseCode(200);
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
        // Make sure result delete time isn't expired
        $results = TableRegistry::get('Results');

        $result = $results->get(3);
        $result->set('created', new Time('today'));

        $results->save($result);

        $this->_setAuthSession(3);
        $this->_setAjaxRequest();

        $this->delete('/api/clubs/1/results/3.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteTimeExpired()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();

        $this->delete('/api/clubs/1/results/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_setAuthSession(4);
        $this->_setAjaxRequest();

        $this->delete('/api/clubs/1/results/7.json');

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
