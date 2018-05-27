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
        if ($this->request->getParam('match_id') &&
            !$this->Disputes->Matches
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
        if ($this->request->getParam('action') === 'close' &&
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
            $this->request->getParam('action') === 'close'
        ) {
            // Closed
            if ($this->Disputes->isClosed($this->request->getParam('match_id'))) {
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

        $this->Disputes->patchEntityAdd(
            $dispute,
            $this->request->getData(),
            $this->request->getParam('match_id')
        );

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
    public function close()
    {
        $dispute = $this->Disputes->get($this->request->getParam('match_id'));

        $this->Disputes->patchEntityEdit($dispute, $this->request->getData());

        $success = $this->Disputes->close($dispute);

        $this->set([
            'dispute' => $dispute,
            'errors' => $dispute->errors()
        ]);

        if (!$success) {
            $this->response->statusCode(400);
            return;
        }

        // Get updated club and matches
        $club = $this->Disputes->Matches->Clubs->get($this->request->getParam('club_id'), ['finder' => 'banditId']);
        $match = $this->Disputes->Matches->get($dispute->match_id);
        $matches = $this->Disputes->Matches
            ->find('tree', ['match' => $match])
            ->find('populated');

        $this->set([
            'club' => $club,
            'matches' => $matches
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete()
    {
        $dispute = $this->Disputes->get($this->request->getParam('match_id'));

        $this->Disputes->delete($dispute);

        $this->set('dispute', $dispute);
    }

    /**
     * @return void
     */
    public function index()
    {
        $disputes = $this->Disputes
            ->find('byUserId', ['userId' => $this->Auth->user('id')])
            ->find('withinLastWeek');

        $this->set('disputes', $disputes);
    }
}
