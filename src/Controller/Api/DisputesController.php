<?php

namespace App\Controller\Api;

class DisputesController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        // Invalid result id
        if (!$this->Disputes->Results
                ->isOwnedBy(
                    $this->request->params['result_id'],
                    $this->request->params['club_id']
                )
        ) {
            return false;
        }

        if ($this->request->action === 'add') {
            // Time expired
            if (!$this->Disputes->Results->wasWithinLast($this->request->params['result_id'], '24 hours')) {
                return false;
            }

            // Existing dispute
            if ($this->Disputes->Results->isDisputed($this->request->params['result_id'])) {
                return false;
            }
        }

        // Invalid player a
        if ($this->request->action === 'edit' &&
            !$this->Disputes->Results->wasCreatedBy($this->request->params['result_id'], $this->Auth->user('id'))
        ) {
            return false;
        }

        // Invalid player b
        if (($this->request->action === 'add' || $this->request->action === 'delete') &&
            !$this->Disputes->Results->isAgainst($this->request->params['result_id'], $this->Auth->user('id'))
        ) {
            return false;
        }

        if ($this->request->action === 'delete' ||
            $this->request->action === 'edit'
        ) {
            // Closed
            if ($this->Disputes->isClosed($this->request->params['id'])) {
                return false;
            }

            // Time expired
            if (!$this->Disputes->Results->wasWithinLast($this->request->params['result_id'], '48 hours')) {
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
    }

    /**
     * @return void
     */
    public function delete()
    {
    }

    /**
     * @return void
     */
    public function edit()
    {
    }
}
