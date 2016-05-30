<?php

namespace App\Controller;

class BoxesController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if (!parent::isAuthorized($user)) {
            return false;
        }

        // Invalid club id
        if (!$this->Boxes->BoxLeagueCycles->isOwnedBy($this->request->params['box_league_cycle_id'], $this->request->params['club_id'])) {
            return false;
        }

        // Non founder
        if (!$this->Boxes->Players->isFounder($this->Auth->user('player.id'), $this->request->params['club_id'])) {
            return false;
        }

        // Running cycle
        if ($this->Boxes->BoxLeagueCycles->Clubs->hasUnfinishedBoxLeagueCycle($this->request->params['club_id'])) {
            return false;
        }

        if ($this->request->action === 'delete') {
            // Existing players
            if ($this->Boxes->hasAssignedPlayers($this->request->params['id'])) {
                return false;
            }

            // Only two boxes
            if ($this->Boxes->BoxLeagueCycles->hasMinimumBoxes($this->request->params['box_league_cycle_id'])) {
                return false;
            }
        }

        return true;
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

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete($id)
    {
        $box = $this->Boxes->get($id, [
            'conditions' => [
                'box_league_cycle_id' => $this->request->params['box_league_cycle_id']
            ]
        ]);

        $this->Boxes->delete($box);

        $this->set('_serialize', true);
    }
}
