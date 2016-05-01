<?php

namespace App\Controller;

use Cake\Utility\Hash;

class ResultsController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if ($this->request->action === 'add') {
            $losingPlayerId = Hash::get($this->request->data, 'losing_player_id');

            if ($losingPlayerId === $this->Auth->user('player.id')) {
                return false;
            }

            if (!$this->Results->Players->isAssignedTo($losingPlayerId, $this->request->params['club_id'])) {
                return false;
            }

            if ($this->Results->Players->hasDisputes($this->Auth->user('player.id'), $this->request->params['club_id'])) {
                return false;
            }
        }

        if ($this->request->action === 'delete') {
            if (!$this->Results->isOwnedBy($this->request->params['id'], $this->Auth->user('player.id'))) {
                return false;
            }

            if ($this->Results->isDisputed($this->request->params['id'])) {
                return false;
            }

            if (!$this->Results->wasWithinLast($this->request->params['id'], '24 hours')) {
                return false;
            }
        }

        return parent::isAuthorized($user);
    }

    /**
     * @return void
     */
    public function add()
    {
        $result = $this->Results->newEntity($this->request->data);
        
        $result->set('club_id', $this->request->params['club_id']);
        $result->set('winning_player_id', $this->Auth->user('player.id'));

        $this->set('result', $result);

        if ($this->Results->save($result)) {
            $this->set('_serialize', 'result');
        } else {
            $this->set([
                'errors' => $result->errors(),
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
        $result = $this->Results->get($id, [
            'conditions' => [
                'club_id' => $this->request->params['club_id']
            ]
        ]);

        $this->Results->delete($result);

        $this->set('_serialize', true);
    }

    /**
     * @return void
     */
    public function index()
    {
        $results = $this->Results
            ->find()
            ->where([
                'club_id' => $this->request->params['club_id']
            ]);

        $this->set([
            'results' => $results,
            '_serialize' => 'results'
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function view($id)
    {
        $result = $this->Results->get($id, [
            'conditions' => [
                'club_id' => $this->request->params['club_id']
            ]
        ]);

        $this->set([
            'result' => $result,
            '_serialize' => 'result'
        ]);
    }
}
