<?php

namespace App\Controller\Api;

class BoxMatchesController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if (!parent::isAuthorized($user)) {
            return false;
        }

        // Invalid club_id
        if (!$this->BoxMatches->Boxes->BoxLeagueCycles->isOwnedBy($this->request->params['box_league_cycle_id'], $this->request->params['club_id'])) {
            return false;
        }

        // Invalid box_id
        if (!$this->BoxMatches->Boxes->isOwnedBy($this->request->params['box_id'], $this->request->params['box_league_cycle_id'])) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    public function add()
    {
        $this->set('_serialize', true);
    }
}
