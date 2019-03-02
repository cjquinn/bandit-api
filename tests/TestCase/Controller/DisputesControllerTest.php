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
    public function testAddUnauthenticated()
    {
        $this->post('/clubs/1/matches/1/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidClubId()
    {
        $this->_setAuthSession(2);

        $this->post('/clubs/2/matches/10/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidMatchId()
    {
        $this->_setAuthSession(1);

        $this->post('/clubs/1/matches/10/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddTimeExpired()
    {
        $this->_setAuthSession(2);

        $this->post('/clubs/1/matches/1/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddExistingDispute()
    {
        $this->_setAuthSession(7);

        $this->post('/clubs/1/matches/6/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidPlayerB()
    {
        $this->_setAuthSession(4);

        $this->post('/clubs/1/matches/7/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAuthSession(2);

        $this->post('/clubs/1/matches/7/disputes.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
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
    public function testCloseUnauthenticated()
    {
        $this->patch('/clubs/1/matches/6/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testCloseInvalidClubId()
    {
        $this->_setAuthSession(6);

        $this->patch('/clubs/2/matches/6/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testCloseInvalidMatchId()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/matches/10/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testCloseInvalidPlayerA()
    {
        $this->_setAuthSession(7);

        $this->patch('/clubs/1/matches/6/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testCloseClosedDispute()
    {
        $this->_setAuthSession(5);

        $this->patch('/clubs/1/matches/5/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testCloseTimeExpired()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/matches/2/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testCloseBadData()
    {
        $this->_setAuthSession(6);

        $this->patch('/clubs/1/matches/6/disputes.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testClosePut()
    {
        $this->_setAuthSession(6);

        $this->patch('/clubs/1/matches/6/disputes.json', ['is_resolved' => true]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testDeleteUnauthenticated()
    {
        $this->delete('/clubs/1/matches/6/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidClubId()
    {
        $this->_setAuthSession(7);

        $this->delete('/clubs/2/matches/6/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidMatchId()
    {
        $this->_setAuthSession(8);

        $this->patch('/clubs/1/matches/10/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidPlayerB()
    {
        $this->_setAuthSession(6);

        $this->delete('/clubs/1/matches/6/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteClosedDispute()
    {
        $this->_setAuthSession(1);

        $this->delete('/clubs/1/matches/5/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteTimeExpired()
    {
        $this->_setAuthSession(2);

        $this->delete('/clubs/1/matches/2/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_setAuthSession(7);

        $this->delete('/clubs/1/matches/6/disputes.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIndexUnauthenticated()
    {
        $this->get('/clubs/1/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testIndexInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/disputes.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/disputes.json');

        $this->assertResponseCode(200);
        $this->assertEquals(2, $this->viewVariable('disputes')->count());
    }
}
