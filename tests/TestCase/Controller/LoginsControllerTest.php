<?php

namespace App\Test\TestCase\Controller;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class LoginsControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

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

        $this->_setAjaxRequest();

        $this->put('/api/auth/activate-account.json?token=123', [
            'password' => ''
        ]);
        
        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testActivateAccountPut()
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

        $this->_setAjaxRequest();

        $this->put('/api/auth/activate-account.json?token=123', [
            'password' => 'password'
        ]);
        
        $this->assertResponseCode(200);
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

        $this->_setAjaxRequest();

        $this->post('/api/auth/login.json', [
            'email' => 'christy@bandit.localhost',
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
        $logins = TableRegistry::get('Logins');
        $login = $logins->get(1);

        $login->set([
            'password' => 'password'
        ]);
        $logins->save($login);

        $this->_setAjaxRequest();

        $this->post('/api/auth/login.json', [
            'email' => 'christy@bandit.localhost',
            'password' => 'password',
            'remember_me' => false
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testLogoutGet()
    {
        $this->_setAjaxRequest();

        $this->get('/api/auth/logout.json');

        $this->assertResponseCode(200);
    }


    /**
     * @return void
     */
    public function testRequestPasswordResetInvalidEmail()
    {
        $this->_setAjaxRequest();

        $this->put('/api/auth/request-password-reset.json', [
            'email' => 'incorrect@bandit.localhost'
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetPost()
    {
        $this->_setAjaxRequest();

        $this->put('/api/auth/request-password-reset.json', [
            'email' => 'christy@bandit.localhost'
        ]);
        
        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testResetPasswordBadData()
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

        $this->_setAjaxRequest();

        $this->put('/api/auth/reset-password.json?token=123', [
            'password' => ''
        ]);
        
        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testResetPasswordPut()
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

        $this->_setAjaxRequest();

        $this->put('/api/auth/reset-password.json?token=123', [
            'password' => 'password'
        ]);
        
        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testValidateTokenNoToken()
    {
        $this->_setAjaxRequest();

        $this->get('/api/auth/activate-account/validate-token.json');
        
        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testValidateTokenExpiredToken()
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

        $this->_setAjaxRequest();

        $this->get('/api/auth/activate-account/validate-token.json?token=123');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testValidateTokenActiveAccount()
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

        $this->get('/api/auth/activate-account/validate-token.json?token=123');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testValidateTokenGet()
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

        $logins->save($login);

        $this->get('/api/auth/activate-account/validate-token.json?token=123');

        $this->assertResponseCode(200);
    }
}
