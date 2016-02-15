<?php

namespace App\Controller;

class DisputesController extends AppController
{

    /**
     * @return void
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
        if ($this->request->action === 'add' &&
            $this->_result->winning_player_id === $this->Auth->user('player.id')
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
}
