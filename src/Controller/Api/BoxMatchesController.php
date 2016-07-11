<?php

namespace App\Controller\Api;

use Cake\Utility\Hash;

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

        if ($this->request->action === 'add') {
            $losingPlayerId = Hash::get($this->request->data, 'losing_player_id');

            // Invalid losing player
            if ($this->Auth->user('player.id') === $losingPlayerId) {
                return false;
            }

            // Non box winning player
            if (!$this->BoxMatches->Players->isAssignedToBox($this->Auth->user('player.id'), $this->request->params['box_id'])) {
                return false;
            }

            // Non club losing player
            if (!$this->BoxMatches->Players->isAssignedToClub($losingPlayerId, $this->request->params['club_id'])) {
                return false;
            }

            // Non box losing player
            if (!$this->BoxMatches->Players->isAssignedToBox($losingPlayerId, $this->request->params['box_id'])) {
                return false;
            }

            // Duplicate
            if ($this->BoxMatches->exists([
                    'box_id' => $this->request->params['box_id'],
                    'losing_player_id' => $losingPlayerId,
                    'winning_player_id' => $this->Auth->user('player.id')
                ])
            ) {
                return false;
            }

            // Existing disputes
            if ($this->BoxMatches->Players->hasDisputes($this->Auth->user('player.id'), $this->request->params['club_id'])) {
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
        $boxMatch = $this->BoxMatches->newEntity($this->request->data);

        $boxMatch->set('box_id', $this->request->params['box_id']);
        $boxMatch->set('winning_player_id', $this->Auth->user('player.id'));

        $this->set('boxMatch', $boxMatch);

        if ($this->BoxMatches->save($boxMatch)) {
            $this->set('_serialize', 'boxMatch');
        } else {
            $this->set([
                'errors' => $result->errors(),
                '_serialize' => true
            ]);

            $this->response->statusCode(400);
        }
    }
}
