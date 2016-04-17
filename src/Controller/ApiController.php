<?php

namespace App\Controller;

class ApiController extends AppController
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
        if (!$this->request->is('ajax') ||
            !$this->request->is('json')
        ) {
            return false;
        }

        if (isset($this->request->params['club_id'])) {
            $playersTable = $this->name === 'Players' ? $this->Players : $this->{$this->name}->Players;

            if (!$playersTable->isAssignedTo($this->Auth->user('player.id'), $this->request->params['club_id'])) {
                return false;
            }
        }

        return parent::isAuthorized($user);
    }
}
