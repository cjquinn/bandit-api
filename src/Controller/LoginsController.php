<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
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
            'resetPassword'
        ];
        $bypassActions = array_replace($allowedActions, [1 => 'login']);

        $this->Auth->allow($allowedActions);

        if ($this->Auth->user() && in_array($this->request->action, $bypassActions)) {
            return $this->redirect($this->Auth->redirectUrl());
        }
    }

    /**
     * @return void
     */
    public function activateAccount()
    {
        $login = $this->Logins->validateToken($this->request->query);

        if (!$login) {
            return $this->redirect([
                'action' => 'login'
            ]);
        }

        if (!$login->token_sent->wasWithinLast('1 Hour')) {
            $this->Logins->createToken($login);
            $this->Logins->save($login);

            if (!defined('TESTING')) {
                $this->getMailer('Login')->send('activateAccount', [$login]);
            }

            $this->Flash->info('Your account activation link has expired, please check your email for your new link');

            return $this->redirect([
                'action' => 'login'
            ]);
        }

        if ($login->is_activated) {
            $this->Flash->info('Your account is already active, <a href="' . Router::url(['controller' => 'Logins', 'action' => 'requestPasswordReset']) . '">forgotten password?</a>');

            return $this->redirect([
                'action' => 'login'
            ]);
        }

        if ($this->request->is('put')) {
            $this->Logins->activateAccount($login, $this->request->data);

            if (!$login->errors() &&
                !empty(Hash::get($this->request->data, 'losing_profile_picture.tmp_name')) &&
                !empty(Hash::get($this->request->data, 'winning_profile_picture.tmp_name'))
            ) {
                $this->Logins->Players->setProfilePicture($login->player, $this->request->data['losing_profile_picture']['tmp_name'], 'losing');

                $this->Logins->Players->setProfilePicture($login->player, $this->request->data['winning_profile_picture']['tmp_name'], 'winning');
            }

            if ($this->Logins->save($login)) {
                $this->Flash->success('Account activated, please login with your password');
            
                return $this->redirect([
                    'action' => 'login',
                    '?' => [
                        'email' => $login->email
                    ]
                ]);
            }

            $this->Flash->error('There was an error, please try again');
        }


        $this->set('login', $login);
    }

    /**
     * @return void
     */
    public function login()
    {
        $login = $this->Logins->newEntity();

        if ($this->request->is('post')) {
            $login = $this->Auth->identify();

            if ($login) {
                $this->Auth->setUser($login);

                return $this->redirect($this->Auth->redirectUrl());
            }

            $this->Flash->error('Invalid email or password, please try again');
        }

        if (isset($this->request->query['email'])) {
            $this->request->data['email'] = $this->request->query['email'];
        }

        $this->set('login', $login);
    }

    /**
     * @return void
     */
    public function logout()
    {
        $logoutRedirect = $this->Auth->logout();

        return $this->redirect($logoutRedirect);
    }

    /**
     * @return void
     */
    public function requestPasswordReset()
    {
        if ($this->request->is('post')) {
            $login = $this->Logins
                ->findByEmail($this->request->data['email'])
                ->first();

            if ($login) {
                $this->Logins->createToken($login);
                $this->Logins->save($login);

                if (!defined('TESTING')) {
                    $this->getMailer('Login')->send('resetPassword', [$login]);
                }

                $this->Flash->success('Password reset requested, please check your email');
                
                return $this->redirect([
                    'action' => 'login'
                ]);
            }

            $this->Flash->error('Invalid email, please try again');
        }
    }

    /**
     * @return void
     */
    public function resetPassword()
    {
        $login = $this->Logins->validateToken($this->request->query);

        if (!$login || !$login->token_sent->wasWithinLast('1 Hour')) {
            return $this->redirect([
                'action' => 'login'
            ]);
        }

        if (!$login->token_sent->wasWithinLast('1 Hour')) {
            $this->Flash->info('Your password reset request has expired, please enter your email to try again');

            return $this->redirect([
                'action' => 'requestPasswordReset'
            ]);
        }

        if ($this->request->is('post')) {
            $this->Logins->setPassword($login, $this->request->data);

            if ($this->Logins->save($login)) {
                $this->Flash->success('Password reset, please login with your new password');
            
                return $this->redirect([
                    'action' => 'login'
                ]);
            }

            $this->Flash->error('You must enter a password, please try again');
        }

        $this->set('login', $login);
    }
}
