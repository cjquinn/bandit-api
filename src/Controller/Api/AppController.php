<?php

namespace App\Controller\Api;

use App\Controller\AppController as BaseAppController;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class AppController extends BaseAppController
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
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->config('unauthorizedRedirect', false);
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
            if (!TableRegistry::get('Players')->isAssignedTo($this->Auth->user('player.id'), $this->request->params['club_id'])) {
                return false;
            }
        }

        return parent::isAuthorized($user);
    }
}
