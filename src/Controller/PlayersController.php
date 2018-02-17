<?php

namespace App\Controller;

class PlayersController extends AppController
{

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        if (in_array($this->request->getParam('action'), ['add', 'toggleActive']) &&
            !$this->Players->Clubs->isOwnedBy($this->request->getParam('club_id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        if ($this->request->getParam('action') === 'toggleActive' &&
            (int)$this->request->getParam('id') === $this->Auth->user('id')
        ) {
            return false;
        }

        return parent::isAuthorized($user);
    }

    /**
     * @return void\Cake\Network\Response
     */
    public function add()
    {
        $player = $this->Players->newEntity();

        $player->set('club_id', $this->request->getParam('club_id'));

        $this->Players->patchEntityAdd($player, $this->request->getData());

        if (!$this->Players->save($player)) {
            $this->response->statusCode(400);
        }

        $this->set([
            'player' => $player,
            'errors' => $player->errors()
        ]);
    }

    /**
     * @return void
     */
    public function index()
    {
        // TODO: add filtering and pagination
        // TODO: Make custom finder method
        $players = $this->Players
            ->findByClubId($this->request->getParam('club_id'))
            ->contain(['Users'])
            ->innerJoinWith('Users', function ($q) {
                $q->find('auth');

                return $q;
            });

        $this->set('players', $players);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function toggleActive($id)
    {
        $player = $this->Players->get($id, [
            'conditions' => [
                'club_id' => $this->request->getParam('club_id')
            ]
        ]);

        $player->set('is_active', !$player->is_active);

        $this->Players->save($player);

        $this->set('player', $player);
    }

    /**
     * @return void
     */
    public function view()
    {
        // TODO: implement
    }
}
