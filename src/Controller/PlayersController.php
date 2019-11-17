<?php

namespace App\Controller;

class PlayersController extends AppController
{
    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        if ($this->request->getParam('id') &&
            !$this->Players->isOwnedBy($this->request->getParam('id'), $this->request->getParam('club_id'))
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
        $player = $this->Players->newEntity();

        $this->Players->patchEntityAdd($player, $this->request->getData(), $this->request->getParam('club_id'));

        if (!$this->Players->save($player)) {
            $this->response->statusCode(400);
        }

        $this->set([
            'player' => $player,
            'errors' => $player->getErrors()
        ]);
    }

    /**
     * @return void
     */
    public function index()
    {
        $players = $this->Players
            ->findByClubId($this->request->getParam('club_id'))
            ->find('populated');

        $this->set([
            'players' => $players,
            'total' => $this->request->paging['Players']['count']
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function view($id)
    {
        $player = $this->Players
            ->findById($id)
            ->find('populated')
            ->find('withHighestRating')
            ->firstOrFail();

        $this->set('player', $player);
    }
}
