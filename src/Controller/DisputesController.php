<?php

namespace App\Controller;

class DisputesController extends AppController
{

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        // Invalid match id
        if (!$this->Disputes->Matches
                ->isOwnedBy(
                    $this->request->getParam('match_id'),
                    $this->request->getParam('club_id')
                )
        ) {
            return false;
        }

        if ($this->request->getParam('action') === 'add') {
            // Time expired
            if (!$this->Disputes->Matches->wasWithinLast($this->request->getParam('match_id'), '24 hours')) {
                return false;
            }

            // Existing dispute
            if ($this->Disputes->Matches->isDisputed($this->request->getParam('match_id'))) {
                return false;
            }
        }

        // Invalid player a
        if ($this->request->getParam('action') === 'edit' &&
            !$this->Disputes->Matches->wasCreatedBy($this->request->getParam('match_id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        // Invalid player b
        if (($this->request->getParam('action') === 'add' || $this->request->getParam('action') === 'delete') &&
            !$this->Disputes->Matches->isAgainst($this->request->getParam('match_id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        if ($this->request->getParam('action') === 'delete' ||
            $this->request->getParam('action') === 'edit'
        ) {
            // Closed
            if ($this->Disputes->isClosed($this->request->getParam('id'))) {
                return false;
            }

            // Time expired
            if (!$this->Disputes->Matches->wasWithinLast($this->request->getParam('match_id'), '48 hours')) {
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
        $dispute = $this->Disputes->newEntity();

        // TODO: make patchEntityAdd take a match_id
        $dispute->set('match_id', $this->request->getParam('match_id'));

        $this->Disputes->patchEntityAdd($dispute, $this->request->getData());

        if (!$this->Disputes->save($dispute)) {
            $this->response->statusCode(400);
        }

        $this->set([
            'dispute' => $dispute,
            'errors' => $dispute->errors()
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete($id)
    {
        $dispute = $this->Disputes->get($id);

        $this->Disputes->delete($dispute);

        $this->set('dispute', $dispute);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function edit($id)
    {
        // TODO: rename action to resolve
        $dispute = $this->Disputes->get($id);

        $this->Disputes->patchEntityEdit($dispute, $this->request->getData());

        if ($this->Disputes->close($dispute)) {
            // Get updated matches
            $match = $this->Disputes->Matches->get($dispute->match_id);
            $matches = $this->Disputes->Matches
                ->find('tree', ['match' => $match])
                ->find('populated');

            $this->set('matches', $matches);
        } else {
            $this->response->statusCode(400);
        }

        $this->set([
            'dispute' => $dispute,
            'errors' => $dispute->errors()
        ]);
    }

    /**
     * @return void
     */
    public function index()
    {
        // TODO: implement
    }

    /**
     * @return void
     */
    public function view()
    {
        // TODO: implement
    }
}
