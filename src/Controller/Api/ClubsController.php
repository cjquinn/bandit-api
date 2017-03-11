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
            !$this->Clubs->Players->isFounder($this->Auth->user('player.id'), $this->request->params['id'])
        ) {
            return false;
        }

        // Unassigned
        if ($this->request->action === 'view' &&
            !$this->Clubs->Players->isAssignedToClub($this->Auth->user('player.id'), $this->request->params['id'])
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
            $this->request->data['founding_player_id'] = $user['player']['id'];

            $fieldList[] = 'founding_player_id';
        } else {
            $associated = [
                'FoundingPlayers.Logins'
            ];
            $fieldList[] = 'founding_player';
        }

        $club = $this->Clubs->newEntity($this->request->data, [
            'associated' => $associated,
            'fieldList' => $fieldList
        ]);

        if (!$this->Clubs->save($club)) {
            $this->response->statusCode(400);
        } elseif ($club->player) {
            $this->set(
                'token',
                $this->Clubs->Players->Logins->generateToken($club->player->login_id)
            );
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
                    'Players.id' => $this->Auth->user('player.id')
                ]);

                return $q;
            });

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
