<?php

namespace App\Controller;

use Cake\Utility\Hash;

class ResultsController extends AppController
{

    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
    }

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if ($this->request->action === 'add' &&
            Hash::get($this->request->data, 'loser_id') === $this->Auth->user('player.id')
        ) {
            return false;
        }

        return parent::isAuthorized($user);
    }

    /**
     * @return void
     */
    public function add()
    {
        $result = $this->Results->newEntity($this->request->data);
        $result->set('winner_id', $this->Auth->user('player.id'));

        $this->set('result', $result);

        if ($this->Results->save($result)) {
            $this->set('_serialize', 'result');
        } else {
            $this->set([
                'errors' => $result->errors(),
                '_serialize' => true
            ]);

            $this->response->statusCode(400);
        }
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function delete($id)
    {
    }

    /**
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function edit($id)
    {
    }

    /**
     * @return void
     */
    public function index()
    {
    }

    /**
     * @return void
     */
    public function view()
    {
    }
}
