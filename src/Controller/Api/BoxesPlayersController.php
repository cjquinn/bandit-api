<?php

namespace App\Controller\Api;

class BoxesPlayersController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if (!parent::isAuthorized($user)) {
            return false;
        }

        // Non founder
        if (!$this->BoxesPlayers->Players->isFounder($this->Auth->user('player.id'), $this->request->params['club_id'])) {
            return false;
        }

        // Running cycle
        if ($this->BoxesPlayers->BoxLeagueCycles->Clubs->hasUnfinishedBoxLeagueCycle($this->request->params['club_id'])) {
            return false;
        }

        // Invalid player id
        if (!$this->BoxesPlayers->Players->isAssignedTo($this->request->params['player_id'], $this->request->params['club_id'])) {
            return false;
        }

        if ($this->request->action === 'add') {
            // Invalid club_id
            if (!$this->BoxesPlayers->BoxLeagueCycles->isOwnedBy($this->request->params['box_league_cycle_id'], $this->request->params['club_id'])) {
                return false;
            }

            // Invalid box_id
            if (!$this->BoxesPlayers->Boxes->isOwnedBy($this->request->params['box_id'], $this->request->params['box_league_cycle_id'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return void
     */
    public function add($boxId, $boxLeagueCycleId, $playerId)
    {
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete($boxId, $boxLeagueCycleId, $playerId)
    {
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function edit($boxId, $boxLeagueCycleId, $playerId)
    {
    }
}
