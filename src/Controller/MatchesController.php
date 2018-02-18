<?php

namespace App\Controller;

class MatchesController extends AppController
{
    public $paginate = [
        'limit' => 10,
        'order' => ['Matches.created' => 'DESC']
    ];

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Paginator');
    }

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

        $this->Matches->patchEntityAdd(
            $match,
            $this->request->getData(),
            $this->request->getParam('club_id'),
            $this->Matches->Clubs->getPlayerId(
                $this->request->getParam('club_id'),
                $this->Auth->user('id')
            )
        );

        if (!$this->Matches->save($match)) {
            $this->response->statusCode(400);
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
        $match = $this->Matches->get($id);

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
        $matches = $this->paginate($this->Matches->find('populated'));

        $this->set([
            'matches' => $matches,
            'total' => $this->request->paging['Matches']['count']
        ]);
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function view($id)
    {
        $match = $this->Matches->get($id, ['finder' => 'populated']);

        $this->set('match', $match);
    }
}
