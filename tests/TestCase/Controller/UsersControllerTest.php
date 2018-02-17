<?php

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class UsersControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testActivateAccountNoToken()
    {
        $this->_setAjaxRequest();

        $this->get('/users/activate-account.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testActivateAccountExpiredToken()
    {
        $users = TableRegistry::get('Users');
        $user = $users->get(1);

        $user->set([
            'password' => null,
            'token' => 123,
            'token_sent' => new Time('2 hours ago')
        ], [
            'guard' => false
        ]);
        $users->save($user);

        $this->_setAjaxRequest();

        $this->get('/users/activate-account.json?token=123');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testActivateAccountActiveAccount()
    {
        $users = TableRegistry::get('Users');
        $user = $users->get(1);

        $user->set([
            'password' => 'password',
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $users->save($user);

        $this->get('/users/activate-account.json?token=123');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testActivateAccountGet()
    {
        $users = TableRegistry::get('Users');
        $user = $users->get(1);

        $user->set([
            'password' => null,
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $users->save($user);

        $users->save($user);

        $this->get('/users/activate-account.json?token=123');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testActivateAccountBadData()
    {
        $users = TableRegistry::get('Users');
        $user = $users->get(1);

        $user->set([
            'password' => null,
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $users->save($user);

        $this->_setAjaxRequest();

        $this->patch('/users/activate-account.json?token=123', [
            'password' => ''
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testActivateAccountPatch()
    {
        $users = TableRegistry::get('Users');
        $user = $users->get(1);

        $user->set([
            'password' => null,
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $users->save($user);

        $this->_setAjaxRequest();

        $this->patch('/users/activate-account.json?token=123', [
            'name' => 'Christy Quinn',
            'password' => 'password'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testLoginBadData()
    {
        $this->_setAjaxRequest();

        $this->post('/users/login.json', [
            'email' => 'christy@bandit.play',
            'password' => 'incorrect password',
            'remember_me' => false
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testLoginPost()
    {
        $this->_setAjaxRequest();

        $this->post('/users/login.json', [
            'email' => 'christy@bandit.play',
            'password' => 'password',
            'remember_me' => false
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetInvalidEmail()
    {
        $this->_setAjaxRequest();

        $this->put('/users/request-password-reset.json', [
            'email' => 'incorrect@bandit.localhost'
        ]);

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetPatch()
    {
        $this->_setAjaxRequest();

        $this->patch('/users/request-password-reset.json', [
            'email' => 'christy@bandit.play'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testResetPasswordNoToken()
    {
        $this->_setAjaxRequest();

        $this->get('/users/reset-password.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testResetPasswordExpiredToken()
    {
        $users = TableRegistry::get('Users');
        $user = $users->get(1);

        $user->set([
            'password' => null,
            'token' => 123,
            'token_sent' => new Time('2 hours ago')
        ], [
            'guard' => false
        ]);
        $users->save($user);

        $this->_setAjaxRequest();

        $this->get('/users/reset-password.json?token=123');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testResetPasswordBadData()
    {
        $users = TableRegistry::get('Users');
        $user = $users->get(1);

        $user->set([
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $users->save($user);

        $this->_setAjaxRequest();

        $this->patch('/users/reset-password.json?token=123', [
            'password' => ''
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testResetPasswordPut()
    {
        $users = TableRegistry::get('Users');
        $user = $users->get(1);

        $user->set([
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $users->save($user);

        $this->_setAjaxRequest();

        $this->patch('/users/reset-password.json?token=123', [
            'password' => 'password'
        ]);

        $this->assertResponseCode(200);
    }
}
