<?php

namespace App\Controller\Api;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
use Cake\Network\Exception\ForbiddenException;
use Cake\Routing\Router;
use Cake\Utility\Hash;

class LoginsController extends AppController
{

    use MailerAwareTrait;

    /**
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $allowedActions = [
            'activateAccount',
            'logout',
            'requestPasswordReset',
            'resetPassword',
            'validateToken'
        ];
        $bypassActions = array_replace($allowedActions, [1 => 'login']);

        $this->Auth->allow($allowedActions);

        if ($this->Auth->user() &&
            in_array($this->request->action, $bypassActions)
        ) {
            throw new ForbiddenException('You are not authorized to access that location.');
        }
    }

    /**
     * @return void
     */
    public function activateAccount()
    {
        $login = $this->_validateToken('activateAccount');

        $this->Logins->activateAccount($login, $this->request->data);

        if (!$login->errors() &&
            !empty(Hash::get($this->request->data, 'losing_profile_picture.tmp_name')) &&
            !empty(Hash::get($this->request->data, 'winning_profile_picture.tmp_name'))
        ) {
            $this->Logins->Players->setProfilePicture($login->player, $this->request->data['losing_profile_picture']['tmp_name'], 'losing');

            $this->Logins->Players->setProfilePicture($login->player, $this->request->data['winning_profile_picture']['tmp_name'], 'winning');
        }

        if ($this->Logins->save($login)) {
            $this->set([
                'email' => $login->email,
                '_serialize' => 'email'
            ]);
        } else {
            $this->set([
                'errors' => $login->errors(),
                '_serialize' => 'errors'
            ]);
            
            $this->response->statusCode(400);
        }
    }

    /**
     * @return void
     */
    public function login()
    {
        $login = $this->Auth->identify();

        if ($login) {
            $this->Auth->setUser($login);

            $this->set([
                'login' => $login,
                '_serialize' => 'login'
            ]);
        } else {
            $this->set('_serialize', true);

            $this->response->statusCode(400);
        }
    }

    /**
     * @return void
     */
    public function logout()
    {
        $this->Auth->logout();

        $this->set('_serialize', true);
    }

    /**
     * @return void
     */
    public function requestPasswordReset()
    {
        $login = $this->Logins
            ->findByEmail($this->request->data['email'])
            ->first();

        if ($login) {
            $this->Logins->createToken($login);
            $this->Logins->save($login);

            if (!defined('TESTING')) {
                $this->getMailer('Login')->send('resetPassword', [$login]);
            }
        } else {
            $this->response->statusCode(400);
        }

        $this->set('_serialize', true);
    }

    /**
     * @return void
     */
    public function resetPassword()
    {
        $login = $this->_validateToken('resetPassword');

        $this->Logins->setPassword($login, $this->request->data);

        if (!$this->Logins->save($login)) {
            $this->response->statusCode(400);
        }

        $this->set('_serialize', true);
    }

    /**
     * @return void
     */
    public function validateToken($parentAction)
    {
        $this->_validateToken($parentAction);

        $this->set('_serialize', true);
    }

    /**
     * @return \App\Model\Entity\Login
     */
    private function _validateToken($parentAction)
    {
        $login = $this->Logins->validateToken($this->request->query);

        if (!$login) {
            throw new ForbiddenException('You are not authorized to access that location.');
        }

        if (!$login->token_sent->wasWithinLast('1 Hour')) {
            if ($parentAction === 'activateAccount') {
                $this->Logins->createToken($login);
                $this->Logins->save($login);

                if (!defined('TESTING')) {
                    $this->getMailer('Login')->send('activateAccount', [$login]);
                }

                $message = 'Your account activation link has expired, please check your email for your new link.';
            } else {
                $message = 'Your password reset request has expired, please enter your email to try again.';
            }

            throw new ForbiddenException($message);
        }

        if ($parentAction === 'activateAccount' &&
            $login->is_activated
        ) {
            throw new ForbiddenException('Your account is already active.');
        }

        return $login;
    }
}
