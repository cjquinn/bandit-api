<?php

namespace App\Controller\Api;

use Cake\Event\Event;

class ClubsController extends ApiController
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
        $associated = [];
        $fieldList = ['name'];

        $user = $this->Auth->identify();

        if ($user) {
            $this->request->data['founder_id'] = $user['id'];

            $fieldList[] = 'founder_id';
        } else {
            $associated = ['Founders'];
            $fieldList[] = 'founder';
        }

        $club = $this->Clubs->newEntity($this->request->data, [
            'associated' => $associated,
            'fieldList' => $fieldList
        ]);

        if ($this->Clubs->save($club)) {
            $this->set(
                'token',
                $this->Clubs->Founders->generateJwt($club->founder_id)
            );
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
                    'Players.user_id' => $this->Auth->user('id')
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
