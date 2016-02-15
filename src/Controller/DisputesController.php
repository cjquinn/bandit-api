<?php

namespace App\Controller;

class DisputesController extends AppController
{

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');

        $this->_result = $this->Disputes->Results->get($this->request->params['result_id']);
    }

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if ($this->request->action === 'add') {
            if (!$this->_result->created->wasWithinLast('24 hours')) {
                return false;
            }

            if ($this->_result->winning_player_id === $this->Auth->user('player.id')) {
                return false;
            }
        }

        if ($this->request->action === 'edit') {
            if (!$this->_result->created->wasWithinLast('48 hours')) {
                return false;
            }

            if ($this->_result->winning_player_id !== $this->Auth->user('player.id')) {
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
        $dispute = $this->Disputes->newEntity($this->request->data);

        $dispute->set('player_id', $this->_result->winning_player_id);
        $dispute->set('result_id', $this->_result->id);

        $this->set('dispute', $dispute);

        if ($this->Disputes->save($dispute)) {
            $this->set('_serialize', 'dispute');
        } else {
            $this->set([
                'errors' => $dispute->errors(),
                '_serialize' => true
            ]);

            $this->response->statusCode(400);
        }
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function edit($playerId)
    {
        $dispute = $this->Disputes->get([
            'player_id' => $playerId,
            'result_id' => $this->_result->id
        ]);

        $dispute->accessible('*', false);
        $dispute->accessible('is_resolved', true);

        $this->Disputes->patchEntity($dispute, $this->request->data);

        $this->set('dispute', $dispute);

        if ($this->Disputes->save($dispute)) {
            $this->set('_serialize', true);
        } else {
            $this->set([
                'errors' => $dispute->errors(),
                '_serialize' => true
            ]);

            $this->response->statusCode(400);
        }
    }
}
