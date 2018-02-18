<?php

namespace App\Controller;

use Cake\Event\Event;

class ClubsController extends AppController
{
    /**
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow('add');
    }

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        // Non founder
        if ($this->request->getParam('action') === 'edit' &&
            !$this->Clubs->isOwnedBy($this->request->getParam('id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        // Unassigned
        if ($this->request->getParam('action') === 'view' &&
            !$this->Clubs->hasMember($this->request->getParam('id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        return parent::isAuthorized($user);
    }

    /**
     * @return void
     */
    public function add()
    {
        $club = $this->Clubs->newEntity();
        $user = $this->Auth->identify();

        $this->Clubs->patchEntityAdd($club, $this->request->getData(), $user);

        $success = $this->Clubs->save($club);

        $this->set([
            'club' => $club,
            'errors' => $club->errors()
        ]);

        if (!$success) {
            $this->response->statusCode(400);
            return;
        }

        if (!$user) {
            $this->set('jwt', $this->Clubs->Founders->generateJwt($club->founder_id));
        }
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function edit($id)
    {
        $club = $this->Clubs->get($id);

        $this->Clubs->patchEntityEdit($club, $this->request->getData());

        if (!$this->Clubs->save($club)) {
            $this->response->statusCode(400);
        }

        $this->set([
            'club' => $club,
            'errors' => $club->errors()
        ]);
    }

    /**
     * @return void
     */
    public function index()
    {
        $clubs = $this->Clubs
            ->find('byUserId', ['userId' => $this->Auth->user('id')]);

        $this->set('clubs', $clubs);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function view($id)
    {
        $club = $this->Clubs->get($id);

        $this->set('club', $club);
    }
}
