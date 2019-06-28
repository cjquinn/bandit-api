<?php

namespace App\Controller;

class ChallengesController extends AppController
{
    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        // Invalid challenge id
        if ($this->request->getParam('id') &&
            !$this->Challenges->isOwnedBy(
                $this->request->getParam('id'),
                $this->request->getParam('club_id')
            )
        ) {
            return false;
        }

        // Already accepted
        if ($this->request->getParam('action') === 'accept' &&
            $this->Challenges->isAccepted($this->request->getParam('id'))
        ) {
            return false;
        }

        // Invalid Player a
        if ($this->request->getParam('action') === 'accept' &&
            $this->Challenges->wasCreatedBy($this->request->getParam('id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        if ($this->request->getParam('action') === 'delete' &&
            !$this->Challenges->wasCreatedBy($this->request->getParam('id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        // Invalid Player b
        if ($this->request->getParam('action') === 'withdraw' &&
            !$this->Challenges->wasAcceptedBy($this->request->getParam('id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        // Match played
        if (in_array($this->request->getParam('action'), ['delete', 'withdraw']) &&
            $this->Challenges->hasMatch($this->request->getParam('id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        return parent::isAuthorized($user);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function accept($id)
    {
        $challenge = $this->Challenges->get($id);

        $this->Challenges->accept(
            $challenge,
            $this->Challenges->Clubs->getPlayerId(
                $this->request->getParam('club_id'),
                $this->Auth->user('id')
            )
        );

        $this->set('challenge', $challenge);
    }

    /**
     * @return void
     */
    public function add()
    {
        $challenge = $this->Challenges->newEntity();

        $this->Challenges->patchEntityAdd(
            $challenge,
            $this->request->getData(),
            $this->request->getParam('club_id'),
            $this->Challenges->Clubs->getPlayerId(
                $this->request->getParam('club_id'),
                $this->Auth->user('id')
            )
        );

        if ($this->Challenges->save($challenge)) {
            $this->response->statusCode(400);
        }

        $this->set([
            'challenge' => $challenge,
            'errors' => $challenge->getErrors()
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete($id)
    {
        $challenge = $this->Challenges->get($id);

        $this->Challenges->softDelete($challenge);

        $this->set('challenge', $challenge);
    }

    /**
     * @return void
     */
    public function index()
    {
        $challenges = $this->Challenges->find('filtered', $this->request->getQueryParams());

        $this->set('challenges', $challenges);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function view($id)
    {
        $challenge = $this->Challenges->get($id);

        $this->set('challenge', $challenge);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function withdraw($id)
    {
        $challenge = $this->Challenges->get($id);

        $this->Challenges->withdraw(
            $challenge,
            $this->Challenges->Clubs->getPlayerId(
                $this->request->getParam('club_id'),
                $this->Auth->user('id')
            )
        );

        $this->set('challenge', $challenge);
    }
}
