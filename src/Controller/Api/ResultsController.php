<?php

namespace App\Controller\Api;

use Cake\Utility\Hash;

class ResultsController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        if ($this->request->action === 'add' &&
            $this->Results->Clubs->hasDisputingMember($this->request->params['club_id'], $this->Auth->user('id'))
        ) {
            return false;
        }

        if ($this->request->action === 'delete') {
            // Invalid player
            if (!$this->Results->wasCreatedBy($this->request->params['id'], $this->Auth->user('id'))) {
                return false;
            }

            // Existing dispute
            if ($this->Results->isDisputed($this->request->params['id'])) {
                return false;
            }

            // Time expired
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
        $result = $this->Results->newEntity();

        $result->set('club_id', $this->request->params['club_id']);
        $result->set(
            'player_a_id',
            $this->Results->Clubs->getPlayerId(
                $this->request->params['club_id'],
                $this->Auth->user('id')
            )
        );

        $this->Results->patchEntity($result, $this->request->data);

        if (!$this->Results->save($result)) {
            $this->response->statusCode(400);
        }

        $this->set([
            'result' => $result,
            'errors' => $result->errors()
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete($id)
    {
        $result = $this->Results->get($id, [
            'conditions' => [
                'club_id' => $this->request->params['club_id'],
                'is_deleted' => false
            ]
        ]);

        $this->Results->softDelete($result);

        // Get updated results
        $results = $this->Results
            ->find('tree', ['result' => $result])
            ->find('populated');

        $this->set([
            'result' => $result,
            'results' => $results
        ]);
    }

    /**
     * @return void
     */
    public function index()
    {
        $results = $this->Results
            ->find('populated')
            ->where([
                'Results.club_id' => $this->request->params['club_id'],
                'is_deleted' => false
            ]);

        $this->set('results', $results);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function view($id)
    {
        $result = $this->Results->get($id, [
            'conditions' => [
                'club_id' => $this->request->params['club_id'],
                'is_deleted' => false
            ],
            'finder' => 'populated'
        ]);

        $this->set('result', $result);
    }
}
