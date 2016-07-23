<?php

namespace App\Test\TestCase\Controller\Api;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class DisputesControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/1/results/3/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testUnassigned()
    {
        $clubsPlayers = TableRegistry::get('ClubsPlayers');

        $clubsPlayer = $clubsPlayers->get([
            'club_id' => 1,
            'player_id' => 1
        ]);

        $clubsPlayers->delete($clubsPlayer);

        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/results/3/disputes.json', [
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

        $this->post('/api/clubs/1/results/1/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidLosingPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/api/clubs/1/results/3/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddExistingDispute()
    {
        $results = TableRegistry::get('Results');

        $result = $results->get(2);
        $result->set('submitted', new Time('today'));

        $results->save($result);

        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/results/2/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddBoxMatchResult()
    {
        $results = TableRegistry::get('Results');

        $result = $results->get(3);
        $result->set('box_match_id', 1);

        $results->save($result);

        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/results/3/disputes.json', [
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

        $this->post('/api/clubs/1/results/3/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testDeleteResolvedDispute()
    {
        $disputes = TableRegistry::get('Disputes');
        $dispute = $disputes->get(2);
        $dispute->set('is_resolved', true);
        $disputes->save($dispute);

        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->delete('/api/clubs/1/results/2/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidLosingPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(3);

        $this->delete('/api/clubs/1/results/2/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->delete('/api/clubs/1/results/2/disputes.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testEditTimeExpired()
    {
        $results = TableRegistry::get('Results');
        $result = $results->get(2);
        $result->set('submitted', new Time('3 days ago'));
        $results->save($result);

        $this->_setAjaxRequest();
        $this->_setAuthSession(3);

        $this->put('/api/clubs/1/results/2/disputes.json', [
            'is_resolved' => 1
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testEditInvalidWinningPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->put('/api/clubs/1/results/2/disputes.json', [
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

        $this->put('/api/clubs/1/results/2/disputes.json', [
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

        $this->put('/api/clubs/1/results/2/disputes.json', [
            'is_resolved' => 0
        ]);

        $this->assertResponseCode(200);
    }
}
