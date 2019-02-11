<?php

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\TestSuite\IntegrationTestCase;

class LeaderboardControllerTest extends IntegrationTestCase
{
    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testAllTimeUnauthenticated()
    {
        $this->get('/clubs/1/leaderboards/all-time');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAllTimeInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/leaderboards/all-time');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAllTimeGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/leaderboards/all-time');

        $this->assertResponseCode(200);
        $this->assertEquals(7, $this->viewVariable('players')->count());
    }

    /**
     * @return void
     */
    public function testWeeklyUnauthenticated()
    {
        $this->get('/clubs/1/leaderboards/weekly');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testWeeklyInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/leaderboards/weekly');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testWeeklyGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/leaderboards/weekly');

        $this->assertResponseCode(200);
    }
}
