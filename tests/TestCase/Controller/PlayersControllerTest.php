<?php

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class PlayersControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testAddUnauthorised()
    {
        $this->get('/invite-player');

        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }

    /**
     * @return void
     */
    public function testAddGet()
    {
        $this->_setAuthSession('1');

        $this->get('/invite-player');

        $this->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testAddBadData()
    {
        $this->_setAuthSession('1');

        $this->post('/invite-player', [
            'name' => '',
            'login' => [
                'email' => ''
            ]
        ]);

        $this->assertNoRedirect();
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAuthSession('1');

        $data = [
            'name' => 'Russell Bishop',
            'login' => [
                'email' => 'russell@bandit.localhost'
            ]
        ];

        $this->post('/invite-player', $data);

        $this->assertRedirect([
            'controller' => 'Players',
            'action' => 'add'
        ]);
    }

    /**
     * @return void
     */
    public function testEditUnauthorised()
    {
        $this->get('/profile');

        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }

    /**
     * @return void
     */
    public function testEditGet()
    {
        $this->_setAuthSession('1');

        $this->get('/profile');

        $this->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testEditBadData()
    {
        $this->_setAuthSession('1');

        $this->put('/profile', [
            'name' => '',
            'login' => [
                'email' => ''
            ]
        ]);

        $this->assertNoRedirect();
    }

    /**
     * @return void
     */
    public function testEditPutBasic()
    {
        $this->_setAuthSession('1');

        $this->put('/profile', [
            'name' => 'Christy J Quinn',
            'login' => [
                'email' => 'quinn@bandit.localhost'
            ]
        ]);

        $player = TableRegistry::get('Players')->get(1, [
            'contain' => [
                'Logins'
            ]
        ]);

        $this->assertResponseOk();

        $this->assertEquals('Christy J Quinn', $player->name);
        $this->assertEquals('quinn@bandit.localhost', $player->login->email);
    }

    /**
     * @return void
     */
    public function testEditPutFile()
    {
        $this->_setAuthSession('1');

        $this->put('/profile', [
            'winning_profile_picture' => [
                'name' => 'test.jpg',
                'tmp_name' => TESTS . 'test.jpg',
                'type' => 'image/jpeg',
                'error' => 0,
                'size' => 127807
            ]
        ]);

        $player = TableRegistry::get('Players')->get(1, [
            'contain' => [
                'WinningProfilePictures'
            ]
        ]);

        $this->assertResponseOk();
        $this->assertNotEmpty($player->winning_profile_picture);
    }
}
