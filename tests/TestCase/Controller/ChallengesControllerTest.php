<?php

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\TestSuite\IntegrationTestCase;

class ChallengesControllerTest extends IntegrationTestCase
{
    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testAcceptUnauthenticated()
    {
        $this->patch('/clubs/1/challenges/1/accept.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAcceptInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->patch('/clubs/1/challenges/1/accept.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAcceptInvalidChallengeId()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/2/accept.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAcceptExistingPlayerBId()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/3/accept.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAcceptInvalidPlayer()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/1/accept.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAcceptPastChallenge()
    {
        $this->_setAuthSession(8);

        $this->patch('/clubs/2/challenges/4/accept.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAcceptDeleted()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/6/accept.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testAcceptPatch()
    {
        $this->_setAuthSession(2);

        $this->patch('/clubs/1/challenges/1/accept.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testAddUnauthenticated()
    {
        $this->post('/clubs/1/challenges.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->post('/clubs/1/challenges.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
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
     */
    public function testDeleteUnauthenticated()
    {
        $this->delete('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->delete('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidChallengeId()
    {
        $this->_setAuthSession(1);

        $this->delete('/clubs/1/challenges/4.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidPlayer()
    {
        $this->_setAuthSession(2);

        $this->delete('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteMatchPlayed()
    {
        $this->_setAuthSession(1);

        $this->delete('/clubs/1/challenges/5.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_setAuthSession(1);

        $this->delete('/clubs/1/challenges/1.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIndexUnauthenticated()
    {
        $this->get('/clubs/1/challenges.json?filter=open');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testIndexInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/challenges.json?filter=open');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/challenges.json?filter=open');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testReportUnauthenticated()
    {
        $this->patch('/clubs/1/challenges/3/report.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testReportInvalidClubId()
    {
        $this->_table('Challenges')->updateAll(
            ['player_a_id' => 8],
            ['id' => 3]
        );
        $this->_setAuthSession(8);

        $this->patch('/clubs/1/challenges/3/report.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testReportInvalidChallengeId()
    {
        $this->_table('Challenges')->updateAll(
            ['player_a_id' => 1],
            ['id' => 3]
        );
        $this->_setAuthSession(1);

        $this->patch('/clubs/2/challenges/3/report.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testReportUnacceptedChallenge()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/2/challenges/4/report.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testReportInvalidPlayer()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/3/report.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testReportMatchPlayed()
    {
        $this->_table('Challenges')->updateAll(
            ['match_datetime' => date('Y-m-d H:i:s', strtotime('-1 hour'))],
            ['id' => 5]
        );
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/5/report.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testReportDeleted()
    {
        $this->_table('Challenges')->updateAll(
            ['match_datetime' => date('Y-m-d H:i:s', strtotime('-1 hour'))],
            ['id' => 8]
        );
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/8/report.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testReportFutureChallenge()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/5/report.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testReportPatch()
    {
        $this->_setAuthSession(2);

        $this->patch('/clubs/1/challenges/3/report.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testViewUnauthenticated()
    {
        $this->get('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/challenges/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewInvalidChallengeId()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/challenges/2.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewDeleted()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/challenges/6.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testViewGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/challenges/1.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testWithdrawUnauthenticated()
    {
        $this->patch('/clubs/1/challenges/7/withdraw.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testWithdrawInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->patch('/clubs/1/challenges/7/withdraw.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testWithdrawInvalidChallengeId()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/2/challenges/7/withdraw.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testWithdrawInvalidPlayer()
    {
        $this->_setAuthSession(2);

        $this->patch('/clubs/1/challenges/7/withdraw.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testWithdrawMatchPlayed()
    {
        $this->_setAuthSession(2);

        $this->patch('/clubs/1/challenges/5/withdraw.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testWithdrawDeleted()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/8/withdraw.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testWithdrawPatch()
    {
        $this->_setAuthSession(1);

        $this->patch('/clubs/1/challenges/7/withdraw.json');

        $this->assertResponseCode(200);
    }
}
