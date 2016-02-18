<?php

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

use DateTime;

class DisputesControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testAddUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/results/2/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddTimeExpired()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/results/1/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidPlayerId()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/results/2/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/results/2/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testDeleteUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->delete('/results/3/disputes/3.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteResolvedDispute()
    {
        $disputes = TableRegistry::get('Disputes');
        $dispute = $disputes->get([
            'player_id' => 3,
            'result_id' => 3
        ]);
        $dispute->set('is_resolved', true);
        $disputes->save($dispute);

        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->delete('/results/3/disputes/3.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidPlayerId()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(3);

        $this->delete('/results/3/disputes/3.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->delete('/results/3/disputes/3.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testEditUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->put('/results/3/disputes/3.json', [
            'is_resolved' => 1
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testEditTimeExpired()
    {
        $results = TableRegistry::get('Results');
        $result = $results->get(3);
        $result->set('created', new DateTime('3 days ago'));
        $results->save($result);

        $this->_setAjaxRequest();
        $this->_setAuthSession(3);

        $this->put('/results/3/disputes/3.json', [
            'is_resolved' => 1
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testEditInvalidPlayerId()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->put('/results/3/disputes/3.json', [
            'is_resolved' => 1
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testEditBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(3);

        $this->put('/results/3/disputes/3.json', [
            'is_resolved' => 'Not a boolean!'
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testEditPut()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(3);

        $this->put('/results/3/disputes/3.json', [
            'is_resolved' => 1
        ]);

        $this->assertResponseCode(200);
    }
}
