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
    public function testAddUnauthenticated()
    {
        $this->post('/clubs/1/matches.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->post('/clubs/1/matches.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddExistingDisputes()
    {
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
        $this->_setAuthSession(2);

        $this->post('/clubs/1/matches.json', []);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
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
    public function testDeleteUnauthenticated()
    {
        $this->delete('/clubs/1/matches/7.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->delete('/clubs/1/matches/7.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidMatchId()
    {
        $this->_table('Disputes')->deleteAll(['match_id' => 10]);

        $this->_setAuthSession(1);

        $this->delete('/clubs/1/matches/10.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteInvalidPlayer()
    {
        $this->_setAuthSession(1);

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

        $this->delete('/clubs/1/matches/3.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteTimeExpired()
    {
        $this->_setAuthSession(1);

        $this->delete('/clubs/1/matches/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDeleteDelete()
    {
        $this->_setAuthSession(4);

        $this->delete('/clubs/1/matches/7.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testIndexUnauthenticated()
    {
        $this->get('/clubs/1/matches.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testIndexInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/matches.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testIndexGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/matches.json');

        $this->assertResponseCode(200);
        $this->assertEquals(8, $this->viewVariable('matches')->count());
    }

    /**
     * @return void
     */
    public function testViewUnauthenticated()
    {
        $this->get('/clubs/1/matches/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewInvalidClubId()
    {
        $this->_setAuthSession(8);

        $this->get('/clubs/1/matches/1.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewInvalidMatchId()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/matches/9.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testViewGet()
    {
        $this->_setAuthSession(1);

        $this->get('/clubs/1/matches/1.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testViewDeleted()
    {
        $this->_table('Matches')->updateAll(
            ['deleted' => date('Y-m-d H:i:s')],
            ['id' => 1]
        );

        $this->_setAuthSession(1);

        $this->get('/clubs/1/matches/1.json');

        $this->assertResponseCode(200);
    }
}
