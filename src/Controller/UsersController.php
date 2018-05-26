<?php

namespace App\Controller;

use Cake\Event\Event;

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
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function activateAccount()
    {
        $user = $this->Users->getByToken($this->request->getQuery('token'), false);

        if ($this->request->is('patch')) {
            $this->Users->patchEntityActivate($user, $this->request->data);

            $success = $this->Users->save($user);

            $this->set([
                'user' => $user,
                'errors' => $user->getErrors()
            ]);

            if (!$success) {
                $this->response->statusCode(400);
                return;
            }

            $this->set('jwt', $this->Users->generateJwt($user->id));
        }
    }

    /**
     * @return void
     */
    public function current()
    {
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => 'Players']);

        $this->set('user', $user);
    }

    /**
     * @return void
     */
    public function edit()
    {
        $user = $this->Users->get($this->Auth->user('id'));

        $this->Users->patchEntityEdit($user, $this->request->getData());

        if (!$this->Users->save($user)) {
            $this->response = $this->response->withStatus(400);
        }

        $this->set([
            'user' => $user,
            'errors' => $user->getErrors()
        ]);
    }

    /**
     * @return void
     */
    public function login()
    {
        $user = $this->Auth->identify();

        if (!$user) {
            $this->set('errors', [
                '_error' => [
                    'invalid' => 'Invalid email or password, please try again'
                ]
            ]);

            $this->response = $this->response->withStatus(400);

            return;
        }

        $user = $this->Users->get($user['id'], ['contain' => 'Players']);

        $this->set([
            'jwt' => $this->Users->generateJwt($user->id),
            'user' => $user
        ]);
    }

    /**
     * @return void
     */
    public function requestPasswordReset()
    {
        $user = $this->Users
            ->findByEmail($this->request->getData('email'))
            ->first();

        if (!$user) {
            $this->set('errors', [
                'email' => [
                    'invalid' => 'Invalid email, please try again'
                ]
            ]);

            $this->response = $this->response->withStatus(400);

            return;
        }

        $this->Users->patchEntitySetToken($user);

        $this->Users->save($user);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function resetPassword()
    {
        $user = $this->Users->getByToken($this->request->getQuery('token'), true);

        if ($this->request->is('patch')) {
            $this->Users->patchEntityResetPassword($user, $this->request->getData());

            if (!$this->Users->save($user)) {
                $this->response = $this->response->withStatus(400);
            }

            $this->set([
                'user' => $user,
                'errors' => $user->getErrors()
            ]);
        }
    }
}
