<?php

namespace App\Test\TestCase\Controller\Api;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

use DateTime;

class BoxMatchesControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testUnassigned()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(9);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testInvalidClubId()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/2/box-league-cycles/1/boxes/1/box-matches.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testInvalidBoxId()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/3/box-matches.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidLosingPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 1,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddNonClubLosingPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 9,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddNonBoxWinningPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(5);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 1,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddNonBoxLosingPlayer()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 5,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddDuplicate()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 1,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddExistingDisputes()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(3);

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 1,
            'losses' => 0,
            'wins' => 3
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

        $this->post('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches.json', [
            'losing_player_id' => 3,
            'losses' => 0,
            'wins' => 3
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testDisputeTimeExpired()
    {
        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->put('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches/1/dispute.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDisputeInvalidLosingPlayer()
    {
        $boxMatches = TableRegistry::get('BoxMatches');

        $boxMatch = $boxMatches->get(1);
        $boxMatch->set('submitted', new DateTime());

        $boxMatches->save($boxMatch);

        $this->_setAjaxRequest();
        $this->_setAuthSession(2);

        $this->put('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches/1/dispute.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDisputeExistingDispute()
    {
        $boxMatches = TableRegistry::get('BoxMatches');

        $boxMatch = $boxMatches->get(1);
        $boxMatch->set('submitted', new DateTime());
        $boxMatch->set('disputed', new DateTime());

        $boxMatches->save($boxMatch);

        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->put('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches/1/dispute.json', []);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testDisputePut()
    {
        $boxMatches = TableRegistry::get('BoxMatches');

        $boxMatch = $boxMatches->get(1);
        $boxMatch->set('submitted', new DateTime());

        $boxMatches->save($boxMatch);

        $this->_setAjaxRequest();
        $this->_setAuthSession(1);

        $this->put('/api/clubs/1/box-league-cycles/1/boxes/1/box-matches/1/dispute.json', []);

        $this->assertResponseCode(200);
    }
}
