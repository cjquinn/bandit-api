<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;

// TODO: Replace with Shopanalyst API UsersController
class UsersController extends AppController
{

    /**
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow([
            'activateAccount',
            'login',
            'requestPasswordReset',
            'resetPassword'
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Network\Exception\ForbiddenException
     */
    public function activateAccount()
    {
        $user = $this->Users->getByToken($this->request->query);

        if (!$user->token_sent->wasWithinLast('1 Hour')) {
            $this->Users->patchEntitySetToken($user);

            $this->Users->save($user);

            throw new ForbiddenException(
                'Your account activation link has expired, please check your email for your new link.'
            );
        }

        if ($user->is_activated) {
            throw new ForbiddenException('Your account is already active.');
        }

        if ($this->request->is('patch')) {
            $this->Users->patchEntityActivate($user, $this->request->data);

            if ($this->Users->save($user)) {
                $this->set([
                    'jwt' => $this->Users->generateJwt($user->id),
                    'user' => $user
                ]);
            } else {
                $this->set('errors', $user->errors());

                $this->response->statusCode(400);
            }
        }
    }

    /**
     * @return void
     */
    public function currentUser()
    {
        // TODO: implement
    }

    /**
     * @return void
     */
    public function edit()
    {
        // TODO: implement
    }

    /**
     * @return void
     */
    public function login()
    {
        $user = $this->Auth->identify();

        if ($user) {
            $this->set('user', $user);

            if ($this->request->header('authorization')) {
                $this->set('jwt', $this->Users->generateJwt($user['id']));
            }
        } else {
            if ($this->request->header('authorization')) {
                $this->response->statusCode(403);
            } else {
                $this->set('errors', [
                    '_error' => [
                        'invalid' => 'Invalid email or password, please try again'
                    ]
                ]);

                $this->response->statusCode(400);
            }
        }
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function requestPasswordReset()
    {
        $user = $this->Users
            ->findByEmail($this->request->data['email'])
            ->firstOrFail();

        $this->Users->patchEntitySetToken($user);

        $this->Users->save($user);
    }

    /**
     * @return void
     * @throws \Cake\Network\Exception\ForbiddenException
     */
    public function resetPassword()
    {
        $user = $this->Users->getByToken($this->request->query);

        if (!$user->token_sent->wasWithinLast('1 Hour')) {
            throw new ForbiddenException(
                'Your password reset request has expired, please enter your email to try again.'
            );
        }

        $this->Users->patchEntityPassword($user, $this->request->data);

        if (!$this->Users->save($user)) {
            $this->response->statusCode(400);
        }

        $this->set('_serialize', true);
    }

    /**
     * @return void
     */
    public function uploadAvatar()
    {
        // TODO: implement
    }
}
