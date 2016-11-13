<?php

namespace App\Controller\Api;

class DisputesController extends ApiController
{

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function initialize()
    {
        parent::initialize();
        
        $this->_result = $this->Disputes->Results->get($this->request->params['result_id'], [
            'contain' => [
                'Disputes'
            ]
        ]);
    }

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if (!parent::isAuthorized($user)) {
            return false;
        }

        if ($this->request->action === 'add') {
            // Time expired
            if (!$this->_result->submitted->wasWithinLast('24 hours')) {
                return false;
            }

            // Existing dispute
            if ($this->_result->dispute) {
                return false;
            }
        }

        // Resolved dispute
        if ($this->request->action === 'delete' &&
            $this->_result->dispute->is_resolved
        ) {
            return false;
        }

        // Invalid winning player
        if ($this->request->action === 'edit' &&
            $this->_result->winning_player_id !== $this->Auth->user('player.id')
        ) {
            return false;
        }

        // Invalid losing player
        if (($this->request->action === 'add' || $this->request->action === 'delete') &&
            $this->_result->losing_player_id !== $this->Auth->user('player.id')
        ) {
            return false;
        }

        // Time expired
        if (($this->request->action === 'delete' || $this->request->action === 'edit') &&
            !$this->_result->submitted->wasWithinLast('48 hours')
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    public function add()
    {
        $dispute = $this->Disputes->newEntity($this->request->data);

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
     */
    public function delete()
    {
        $this->Disputes->delete($this->_result->dispute);

        $this->set('_serialize', true);
    }

    /**
     * @return void
     */
    public function edit()
    {
        $this->Disputes->patchEntity($this->_result->dispute, $this->request->data, [
            'fieldList' => [
                'is_resolved'
            ]
        ]);

        $this->set('dispute', $this->_result->dispute);

        if ($this->Disputes->save($this->_result->dispute)) {
            $this->set('_serialize', true);
        } else {
            $this->set([
                'errors' => $this->_result->dispute->errors(),
                '_serialize' => true
            ]);

            $this->response->statusCode(400);
        }
    }
}
