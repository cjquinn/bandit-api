<?php

namespace App\Controller;

class MatchesController extends AppController
{

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        if ($this->request->getParam('action') === 'add' &&
            $this->Matches->Clubs->hasDisputingMember($this->request->getParam('club_id'), $this->Auth->user('id'))
        ) {
            return false;
        }

        if ($this->request->getParam('action') === 'delete') {
            // Invalid player
            if (!$this->Matches->wasCreatedBy($this->request->getParam('id'), $this->Auth->user('id'))) {
                return false;
            }

            // Existing dispute
            if ($this->Matches->isDisputed($this->request->getParam('id'))) {
                return false;
            }

            // Time expired
            if (!$this->Matches->wasWithinLast($this->request->getParam('id'), '24 hours')) {
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
        $match = $this->Matches->newEntity();

        $match->set('club_id', $this->request->getParam('club_id'));
        $match->set(
            'player_a_id',
            $this->Matches->Clubs->getPlayerId(
                $this->request->getParam('club_id'),
                $this->Auth->user('id')
            )
        );

        // TODO: refactor into patchEntityAdd and require club_id and user_id
        $this->Matches->patchEntity($match, $this->request->getData());

        if (!$this->Matches->save($match)) {
            $this->response->statusCode(400);
        } else {
            // TODO: move into after save
            $this->Matches->loadInto($match, [
                'PlayerAs.Users',
                'PlayerBs.Users'
            ]);
        }

        $this->set([
            'match' => $match,
            'errors' => $match->errors()
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete($id)
    {
        $match = $this->Matches->get($id, [
            'conditions' => [
                'club_id' => $this->request->getParam('club_id'),
                // TODO: make use of beforeFind
                'Matches.deleted IS' => null
            ]
        ]);

        // TODO: remove soft delete
        $this->Matches->softDelete($match);

        // Get updated matches
        $matches = $this->Matches
            ->find('tree', ['match' => $match])
            ->find('populated');

        $this->set(['matches' => $matches]);
    }

    /**
     * @return void
     */
    public function index()
    {
        // TODO: integrate pagination
        $matches = $this->Matches
            ->find('populated')
            ->where([
                'Matches.club_id' => $this->request->getParam('club_id'),
                // TODO: make use of beforeFind
                'Matches.deleted IS' => null
            ])
            // TODO: make use of beforeFind
            ->order(['Matches.created' => 'DESC'])
            ->limit(20);

        $this->set('matches', $matches);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function view($id)
    {
        // TODO: populate
        $match = $this->Matches->get($id, [
            'conditions' => [
                'Matches.club_id' => $this->request->getParam('club_id'),
                // TODO: make use of beforeFind
                'Matches.deleted IS' => null
            ],
            'finder' => 'populated'
        ]);

        $this->set('match', $match);
    }
}
