<?php

namespace App\Controller;

class BoxesController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if (!$this->Boxes->BoxLeagueCycles->isOwnedBy($this->request->params['box_league_cycle_id'], $this->request->params['club_id'])) {
            return false;
        }

        if (!$this->Boxes->Players->isFounder($this->Auth->user('player.id'), $this->request->params['club_id'])) {
            return false;
        }

        if ($this->Boxes->BoxLeagueCycles->Clubs->hasUnfinishedBoxLeagueCycle($this->request->params['club_id'])) {
            return false;
        }

        return parent::isAuthorized($user);
    }

    /**
     * @return void
     */
    public function add()
    {
        $box = $this->Boxes->newEntity();

        $box->set('box_league_cycle_id', $this->request->params['box_league_cycle_id']);

        $this->set('box', $box);

        if ($this->Boxes->save($box)) {
            $this->set('_serialize', 'box');
        } else {
            $this->set([
                'errors' => $box->errors(),
                '_serialize' => true
            ]);

            $this->response->statusCode(400);
        }
    }
}
