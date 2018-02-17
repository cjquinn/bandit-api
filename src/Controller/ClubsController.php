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
        if ($this->request->action === 'edit' &&
            !$this->Clubs->isOwnedBy($this->request->params['id'], $this->Auth->user('id'))
        ) {
            return false;
        }

        // Unassigned
        if ($this->request->action === 'view' &&
            !$this->Clubs->hasMember($this->request->params['id'], $this->Auth->user('id'))
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

        $patchEntity = 'patchEntityNewUser';

        if ($user) {
            $this->request->data['founder_id'] = $user['id'];

            $patchEntity = 'patchEntityExistingUser';
        }

        $this->Clubs->{$patchEntity}($club, $this->request->data);

        if ($this->Clubs->save($club)) {
            if (!$user) {
                $this->set(
                    'jwt',
                    $this->Clubs->Founders->generateJwt($club->founder_id)
                );
            }
        } else {
            $this->response->statusCode(400);
        }

        $this->set([
            'club' => $club,
            'errors' => $club->errors()
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function edit($id)
    {
        $club = $this->Clubs->get($id);

        $this->Clubs->patchEntity($club, $this->request->data);

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
            ->find()
            ->innerJoinWith('Players', function ($q) {
                $q->where([
                    'Players.user_id' => $this->Auth->user('id'),
                    'Players.is_active' => true
                ]);

                return $q;
            })
            ->order('Clubs.name');

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
