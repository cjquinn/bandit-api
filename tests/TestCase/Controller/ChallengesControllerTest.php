<?php

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\TestSuite\IntegrationTestCase;

class ChallengesControllerTest extends IntegrationTestCase
{
    use ControllerTestTrait;

    /**
     * @return void
     * @group testing
     */
    public function testAcceptUnauthenticated()
    {
        $this->patch('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAcceptInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->patch('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAcceptInvalidChallengeId()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/2.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAcceptExistingPlayerBId()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/3.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAcceptInvalidPlayer()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAcceptDeleted()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/6.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAcceptPatch()
    {
        $this->_setAuthSession(2);

        $this->patch('/clubs/1/challenges/1.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAddUnauthenticated()
    {
        $this->post('/clubs/1/challenges.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAddInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->post('/clubs/1/challenges.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAddBadData()
    {
        $this->_setAuthSession(1);

        $this->post('/clubs/1/challenges.json', [
            'location' => 'Brixton Leisure Center',
            'match_datetime' => date('Y-m-d H:i:s', strtotime('-1 hour'))
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAddPost()
    {
        $this->_setAuthSession(1);

        $this->post('/clubs/1/challenges.json', [
            'location' => 'Brixton Leisure Center',
            'match_datetime' => date('Y-m-d H:i:s', strtotime('+1 hour'))
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     * @group testing
     */
    public function testDeleteUnauthenticated()
    {
        $this->delete('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testDeleteInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->delete('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testDeleteInvalidChallengeId()
    {
        $this->_setAuthSession(1);

        $this->delete('/clubs/1/challenges/4.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testDeleteInvalidPlayer()
    {
        $this->_setAuthSession(2);

        $this->delete('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testDeleteMatchPlayed()
    {
        $this->_setAuthSession(1);

        $this->delete('/clubs/1/challenges/5.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testDeleteDelete()
    {
        $this->_setAuthSession(1);

        $this->delete('/clubs/1/challenges/1.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     * @group testing
     */
    public function testIndexUnauthenticated()
    {
        $this->get('/clubs/1/challenges.json?filter=open');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testIndexInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/challenges.json?filter=open');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testIndexGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/challenges.json?filter=open');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     * @group testing
     */
    public function testViewUnauthenticated()
    {
        $this->get('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testViewInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testViewInvalidChallengeId()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/challenges/2.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testViewDeleted()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/challenges/6.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     * @group testing
     */
    public function testViewGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/challenges/1.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     * @group testing
     */
    public function testWithdrawUnauthenticated()
    {
        $this->patch('/clubs/1/challenges/7/withdraw.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testWithdrawInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->patch('/clubs/1/challenges/7/withdraw.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testWithdrawInvalidChallengeId()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/2/challenges/7/withdraw.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testWithdrawInvalidPlayer()
    {
        $this->_setAuthSession(2);

        $this->patch('/clubs/1/challenges/7/withdraw.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testWithdrawMatchPlayed()
    {
        $this->_setAuthSession(2);

        $this->patch('/clubs/1/challenges/5/withdraw.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @group testing
     */
    public function testWithdrawDeleted()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/6/withdraw.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     * @group testing
     */
    public function testWithdrawPatch()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/7/withdraw.json');

        $this->assertResponseCode(200);
    }
}
