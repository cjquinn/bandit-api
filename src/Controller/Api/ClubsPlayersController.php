<?php

namespace App\Controller\Api;

class ClubsPlayersController extends AppController
{

    /**
     * @return void
     */
    public function add($clubId, $playerId)
    {
        $clubsPlayer = $this->ClubsPlayers->newEntity([
            'club_id' => $clubId,
            'player_id' => $playerId
        ]);

        if (!$this->ClubsPlayers->save($clubsPlayer)) {
            $this->response->statusCode(400);
        }

        $this->set('_serialize', true);
    }
}
