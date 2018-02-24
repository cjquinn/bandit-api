<?php

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\I18n\Time;
use Cake\TestSuite\IntegrationTestCase;

class MatchesControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_testUnauthorised([
            'post' => '/clubs/1/matches.json',
            'get' => '/clubs/1/matches.json',
            'get' => '/clubs/1/matches.json',
            'delete' => '/clubs/1/matches/1.json',
            'get' => '/clubs/1/matches/1.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAuthorised()
    {
        // For invalid match
        $this->_table('Matches')->updateAll(['club_id' => 2], ['id' => 1]);

        $this->_testAuthorised([
            // Unassigned
            'post' => '/clubs/2/matches.json',
            'get' => '/clubs/2/matches.json',
            'get' => '/clubs/2/matches.json',
            // Invalid match
            'delete' => '/clubs/1/matches/1.json',
            'get' => '/clubs/1/matches/1.json'
        ]);
    }

    /**
     * @return void
     */
    public function testAddExistingDisputes()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/clubs/1/matches.json', [
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

        $this->post('/clubs/1/matches.json', []);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/clubs/1/matches.json', [
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

        $this->delete('/clubs/1/matches/3.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteExistingDispute()
    {
        // Make sure match delete time isn't expired
        $match = $this->_table('Matches')->get(3);
        $match->set('created', new Time('today'));

        $this->_table('Matches')->save($match);

        $this->_setAuthSession(3);
        $this->_setAjaxRequest();

        $this->delete('/clubs/1/matches/3.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteTimeExpired()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();

        $this->delete('/clubs/1/matches/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_setAuthSession(4);
        $this->_setAjaxRequest();

        $this->delete('/clubs/1/matches/7.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/clubs/1/matches.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testViewGet()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->get('/clubs/1/matches/1.json');

        $this->assertResponseCode(200);
    }
}
