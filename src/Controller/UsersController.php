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
            'add',
            'login',
            'requestPasswordReset',
            'resetPassword'
        ]);
    }

    /**
     * @return void
     */
    public function acceptTerms()
    {
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => 'Players']);

        $this->Users->patchEntity(
            $user,
            $this->request->getData(),
            ['validate' => 'acceptTerms']
        );

        if (!$this->Users->save($user)) {
            $this->response = $this->response->withStatus(400);
        }

        $this->set([
            'errors' => $user->getErrors(),
            'user' => $user
        ]);
    }

    /**
     * @return void
     */
    public function add()
    {
        $user = $this->Users->newEntity();

        $this->Users->patchEntityAdd($user, $this->request->getData());

        $success = $this->Users->save($user);

        $this->set([
            'user' => $user,
            'errors' => $user->getErrors()
        ]);

        if (!$success) {
            $this->response = $this->response->withStatus(400);

            return;
        }

        $this->Users->loadInTo($user, ['Players']);

        $this->set('jwt', $this->Users->generateJwt($user->id));
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
        $errors = $this->Users->getValidator('requestPasswordReset')->errors($this->request->getData());

        if (!empty($errors)) {
            $this->set('errors', $errors);

            $this->response = $this->response->withStatus(400);

            return;
        }

        $user = $this->Users
            ->findByEmail($this->request->getData('email'))
            ->first();

        if (!$user) {
            return;
        }

        $this->Users->patchEntitySetToken($user);

        $this->Users->save($user);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Http\Exception\UnauthorizedException
     */
    public function resetPassword()
    {
        $user = $this->Users->getByToken($this->request->getQuery('token'));

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
