<?php

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\TestSuite\IntegrationTestCase;

class LeaderboardControllerTest extends IntegrationTestCase
{
    use ControllerTestTrait;

    /**
     * @return void
     * @group testing
     */
    public function testUnauthorised()
    {
        $this->_testUnauthorised([
            'get' => '/clubs/1/leaderboards/all-time',
            'get' => '/clubs/1/leaderboards/weekly'
        ]);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAuthorised()
    {
        $this->_testAuthorised([
            'get' => '/clubs/2/leaderboards/all-time',
            'get' => '/clubs/2/leaderboards/weekly'
        ]);
    }

    /**
     * @return void
     * @group testing
     */
    public function testAllTime()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/clubs/1/leaderboards/all-time');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     * @group testing
     */
    public function testWeekly()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/clubs/1/leaderboards/weekly');

        $this->assertResponseCode(200);
    }
}
