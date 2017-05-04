<?php

namespace App\Controller\Api;

class PlayersController extends ApiController
{

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
     */
    public function delete($id)
    {
    }
}
