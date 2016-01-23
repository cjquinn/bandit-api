<?php

namespace App\Test\TestCase\Controller;

use Cake\Auth\DefaultPasswordHasher;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Lighthouse\Logins\Controller\UsersController;

class LoginsControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testActivateAccountNoToken()
    {
        $this->get('/activate-account');

        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }

    /**
     * @return void
     */
    public function testActivateAccountExpiredToken()
    {
        $logins = TableRegistry::get('Logins');
        $login = $logins->get(1);

        $login->set([
            'password' => null,
            'token' => 123,
            'token_sent' => new Time('2 hours ago')
        ], [
            'guard' => false
        ]);
        $logins->save($login);

        $this->get('/activate-account?token=123');

        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }

    /**
     * @return void
     */
    public function testActivateAccountActiveAccount()
    {
        $logins = TableRegistry::get('Logins');
        $login = $logins->get(1);

        $login->set([
            'password' => 'password',
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $logins->save($login);

        $this->get('/activate-account?token=123');

        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }

    /**
     * @return void
     */
    public function testActivateAccountBadData()
    {
        $logins = TableRegistry::get('Logins');
        $login = $logins->get(1);

        $login->set([
            'password' => null,
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $logins->save($login);

        $this->post('/activate-account?token=123', [
            'password' => ''
        ]);
        
        $this->assertNoRedirect();
    }

    /**
     * @return void
     */
    public function testActivateAccountPost()
    {
        $logins = TableRegistry::get('Logins');
        $login = $logins->get(1);

        $login->set([
            'password' => null,
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $logins->save($login);

        $this->post('/activate-account?token=123', [
            'password' => 'password'
        ]);
        
        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login',
            '?' => [
                'email' => 'christy@bandit.localhost'
            ]
        ]);
    }

    /**
     * @return void
     */
    public function testActivateAccountGet()
    {
        $logins = TableRegistry::get('Logins');
        $login = $logins->get(1);

        $login->set([
            'password' => null,
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $logins->save($login);

        $this->get('/activate-account?token=123');

        $this->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testLoginBadData()
    {
        $logins = TableRegistry::get('Logins');
        $login = $logins->get(1);

        $login->set([
            'password' => 'password'
        ]);
        $logins->save($login);

        $this->post('/login', [
            'email' => 'christy@bandit.localhost',
            'password' => 'incorrect password',
            'remember_me' => false
        ]);

        $this->assertNoRedirect();
    }

    /**
     * @return void
     */
    public function testLoginPost()
    {
        $logins = TableRegistry::get('Logins');
        $login = $logins->get(1);

        $login->set([
            'password' => 'password'
        ]);
        $logins->save($login);

        $this->post('/login', [
            'email' => 'christy@bandit.localhost',
            'password' => 'password',
            'remember_me' => true
        ]);

        $this->assertRedirect($this->_controller->Auth->redirectUrl());
    }

    /**
     * @return void
     */
    public function testLoginGet()
    {
        $this->get('/login');

        $this->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testLogoutGet()
    {
        $this->get('/logout');

        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }


    /**
     * @return void
     */
    public function testRequestPasswordResetInvalidEmail()
    {
        $this->post('/request-password-reset', [
            'email' => 'incorrect@bandit.localhost'
        ]);

        $this->assertNoRedirect();
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetPost()
    {
        $this->post('/request-password-reset', [
            'email' => 'christy@bandit.localhost'
        ]);
        
        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetGet()
    {
        $this->get('/request-password-reset');

        $this->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testResetPasswordNoToken()
    {
        $this->get('/reset-password');

        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }

    /**
     * @return void
     */
    public function testResetPasswordExpiredToken()
    {
        $logins = TableRegistry::get('Logins');
        $login = $logins->get(1);

        $login->set([
            'token' => 123,
            'token_sent' => new Time('2 hours ago')
        ], [
            'guard' => false
        ]);
        $logins->save($login);

        $this->get('/reset-password?token=123');

        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }

    /**
     * @return void
     */
    public function testResetPasswordGetPost()
    {
        $logins = TableRegistry::get('Logins');
        $login = $logins->get(1);

        $login->set([
            'token' => 123,
            'token_sent' => Time::now()
        ], [
            'guard' => false
        ]);
        $logins->save($login);

        $this->get('/reset-password?token=123');

        $this->assertResponseOk();

        $this->post('/reset-password?token=123', [
            'password' => 'password'
        ]);
        
        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }
}