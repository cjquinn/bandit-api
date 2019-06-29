<?php

namespace App\Controller;

use Cake\Utility\Hash;

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

        return parent::isAuthorized($user);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function accept($id)
    {
        $challenge = $this->Challenges->get($id);

        if (!$this->Challenges->accept(
            $challenge,
            $this->Challenges->Clubs->getPlayerId(
                $this->request->getParam('club_id'),
                $this->Auth->user('id')
            )
        )) {
            $this->response->statusCode(403);
            return;
        }

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

        if (!$this->Challenges->softDelete(
            $challenge,
            $this->Challenges->Clubs->getPlayerId(
                $this->request->getParam('club_id'),
                $this->Auth->user('id')
            )
        )) {
            $this->response->statusCode(403);
            return;
        }

        $this->set('challenge', $challenge);
    }

    /**
     * @return void
     */
    public function index()
    {
        $challenges = $this->Challenges
            ->findByClubId($this->request->getParam('club_id'))
            ->find(
                'filtered',
                ['filter' => Hash::get($request->getQueryParams(), 'filter', null)]
            )
            ->find(
                'byPlayerId',
                ['player_id' => Hash::get($request->getQueryParams(), 'player_id', null)]
            )
            ->find('populated');

        $this->set('challenges', $challenges);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function report($id)
    {
        $challenge = $this->Challenges->get($id);

        if (!$this->Challenges->report(
            $challenge,
            $this->Challenges->Clubs->getPlayerId(
                $this->request->getParam('club_id'),
                $this->Auth->user('id')
            )
        )) {
            $this->response->statusCode(403);
            return;
        }

        $this->set('challenge', $challenge);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function view($id)
    {
        $challenge = $this->Challenges->get($id, ['finder' => 'populated']);

        $this->set('challenge', $challenge);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function withdraw($id)
    {
        $challenge = $this->Challenges->get($id);

        if ($this->Challenges->withdraw(
            $challenge,
            $this->Challenges->Clubs->getPlayerId(
                $this->request->getParam('club_id'),
                $this->Auth->user('id')
            )
        )) {
            $this->response->statusCode(403);
            return;
        }

        $this->set('challenge', $challenge);
    }
}
