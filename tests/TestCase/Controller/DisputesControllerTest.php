<?php

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\TestSuite\IntegrationTestCase;

class DisputesControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_testUnauthorised([
            'post' => '/clubs/1/matches/1/disputes.json',
            'delete' => '/clubs/1/matches/6/disputes/3.json',
            'patch' => '/clubs/1/matches/6/disputes/3/close.json',
            'get' => '/clubs/1/disputes.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAuthorised()
    {
        // Unassigned
        $this->_table('Matches')->updateAll(['club_id' => 2], ['id IN' => [1, 6]]);

        $this->_testAuthorised([
            'post' => '/clubs/2/matches/1/disputes.json',
            'delete' => '/clubs/2/matches/6/disputes/3.json',
            'patch' => '/clubs/2/matches/6/disputes/3/close.json',
            'get' => '/clubs/1/disputes.json'
        ]);

        // Invalid match id
        $this->_testAuthorised([
            'post' => '/clubs/1/matches/1/disputes.json',
            'delete' => '/clubs/1/matches/6/disputes/3.json',
            'patch' => '/clubs/1/matches/6/disputes/3/close.json'
        ]);

        // Invalid dispute id
        $this->_table('Disputes')->updateAll(['match_id' => 1], ['id' => 3]);
        $this->_table('Matches')->updateAll(['club_id' => 1], ['id' => 6]);

        $this->_testAuthorised([
            'delete' => '/clubs/1/matches/6/disputes/3.json',
            'patch' => '/clubs/1/matches/6/disputes/3/close.json'
        ]);
    }

    /**
     * @return void
     */
    public function testUnassigned()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(8);

        $this->post('/clubs/1/matches/7/disputes.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testInvalidMatchId()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(8);

        $this->post('/clubs/2/matches/7/disputes.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddTimeExpired()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/clubs/1/matches/1/disputes.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddExistingDispute()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(7);

        $this->post('/clubs/1/matches/6/disputes.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidPlayerB()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(4);

        $this->post('/clubs/1/matches/7/disputes.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/clubs/1/matches/7/disputes.json', []);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/clubs/1/matches/7/disputes.json', [
            'player_a_score' => 0,
            'player_b_score' => 1
        ]);

        $this->assertResponseCode(200);
    }


    /**
     * @return void
     */
    public function testCloseInvalidPlayerA()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(7);

        $this->patch('/clubs/1/matches/6/disputes/3/close.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testCloseClosedDispute()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(5);

        $this->patch('/clubs/1/matches/5/disputes/2/close.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testCloseTimeExpired()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/matches/2/disputes/1/close.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testCloseBadData()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(6);

        $this->patch('/clubs/1/matches/6/disputes/3/close.json', []);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testClosePut()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(6);

        $this->patch('/clubs/1/matches/6/disputes/3/close.json', [
            'is_resolved' => true
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidPlayerB()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(6);

        $this->delete('/clubs/1/matches/6/disputes/3.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteClosedDispute()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->delete('/clubs/1/matches/5/disputes/2.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteTimeExpired()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->delete('/clubs/1/matches/2/disputes/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(7);

        $this->delete('/clubs/1/matches/6/disputes/3.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIndex()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/clubs/1/disputes.json');

        $this->assertResponseCode(200);
    }
}
