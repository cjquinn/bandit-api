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
        $this->get('/users/activate-account.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testActivateAccountExpiredToken()
    {
        $token = $this->_getToken([
            'isExpired' => true,
            'isActivated' => false
        ]);


        $this->get('/users/activate-account.json?token=' . $token);

        $this->assertResponseCode(401);
    }

    /**
     * @return void
     */
    public function testActivateAccountActiveAccount()
    {
        $token = $this->_getToken([
            'isExpired' => false,
            'isActivated' => true
        ]);


        $this->get('/users/activate-account.json?token=' . $token);

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testActivateAccountGet()
    {
        $token = $this->_getToken([
            'isExpired' => false,
            'isActivated' => false
        ]);


        $this->get('/users/activate-account.json?token=' . $token);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testActivateAccountBadData()
    {
        $token = $this->_getToken([
            'isExpired' => false,
            'isActivated' => false
        ]);


        $this->patch('/users/activate-account.json?token=' . $token, [
            'password' => ''
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testActivateAccountPatch()
    {
        $token = $this->_getToken([
            'isExpired' => false,
            'isActivated' => false
        ]);


        $this->patch('/users/activate-account.json?token=' . $token, [
            'first_name' => 'Christy',
            'last_name' => 'Quinn',
            'password' => 'password'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testCurrentUnauthenticated()
    {
        $this->get('/users/current.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testCurrentGet()
    {
        $this->_setAuthSession(1);

        $this->get('/users/current.json');

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testEditUnauthenticated()
    {
        $this->put('/users/update-settings.json');

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testEditBadData()
    {
        $this->_setAuthSession(1);

        $this->put('/users/update-settings.json', ['email' => '']);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testEditPatch()
    {
        $this->_setAuthSession(1);

        $this->put('/users/update-settings.json', [
            'email' => 'christy@banditmatch.com'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testLoginBadData()
    {

        $this->post('/users/login.json', [
            'email' => 'christy@banditmatch.com',
            'password' => 'incorrect password'
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testLoginPost()
    {

        $this->post('/users/login.json', [
            'email' => 'christy@banditmatch.com',
            'password' => 'password'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetBadData()
    {

        $this->patch('/users/request-password-reset.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetInvalidEmail()
    {

        $this->patch('/users/request-password-reset.json', [
            'email' => 'incorrect@banditmatch.com'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetPatch()
    {

        $this->patch('/users/request-password-reset.json', [
            'email' => 'christy@banditmatch.com'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function testResetPasswordNoToken()
    {

        $this->get('/users/reset-password.json');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testResetPasswordUnactivated()
    {
        $token = $this->_getToken([
            'isExpired' => false,
            'isActivated' => false
        ]);


        $this->get('/users/reset-password.json?token=' . $token);

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     */
    public function testResetPasswordExpiredToken()
    {
        $token = $this->_getToken([
            'isExpired' => true,
            'isActivated' => true
        ]);


        $this->get('/users/reset-password.json?token=' . $token);

        $this->assertResponseCode(401);
    }

    /**
     * @return void
     */
    public function testResetPasswordBadData()
    {
        $token = $this->_getToken([
            'isExpired' => false,
            'isActivated' => true
        ]);


        $this->patch('/users/reset-password.json?token=' . $token, [
            'password' => ''
        ]);

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     */
    public function testResetPasswordPatch()
    {
        $token = $this->_getToken([
            'isExpired' => false,
            'isActivated' => true
        ]);


        $this->patch('/users/reset-password.json?token=' . $token, [
            'password' => 'password'
        ]);

        $this->assertResponseCode(200);
    }

    /**
     * @return void
     */
    public function _getToken($options)
    {
        extract($options);

        $table = $this->_table('Users');

        $user = $table->get(1);
        $table->patchEntitySetToken($user);

        if (isset($isExpired) && $isExpired) {
            $user->token_sent->modify('-3 hours');
        }

        if (isset($isActivated) && !$isActivated) {
            $user->set('password', null);
        }

        $table->save($user);

        return $user->token;
    }
}
