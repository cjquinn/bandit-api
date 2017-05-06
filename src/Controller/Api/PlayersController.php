<?php

namespace App\Controller\Api;

class PlayersController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        if (!$this->Players->Clubs->isOwnedBy($this->request->params['club_id'], $this->Auth->user('id'))) {
            return false;
        }

        if ($this->request->action === 'delete' &&
            (int)$this->request->params['id'] === $this->Auth->user('id')
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

        $player->set('club_id', $this->request->params['club_id']);

        $this->Players->patchEntityAdd($player, $this->request->data);

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
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete($id)
    {
        $player = $this->Players->get($id, [
            'conditions' => [
                'club_id' => $this->request->params['club_id']
            ]
        ]);

        $player->set('is_member', false);

        $this->Players->save($player);

        $this->set('player', $player);
    }
}
