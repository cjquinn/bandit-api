<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller
{

    /**
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('Cookie');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'authenticate' => [
                'all' => [
                    'finder' => 'auth',
                    'userModel' => 'Logins'
                ],
                'Form' => [
                    'fields' => ['username' => 'email']
                ],
                'RememberMe'
            ],
            'authorize' => 'Controller',
            'authError' => false,
            'loginAction' => [
                'controller' => 'Logins',
                'action' => 'login'
            ]
        ]);
    }

    /**
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        if ($this->request->is('ajax')) {
            $this->Auth->config('unauthorizedRedirect', false);
        }
    }

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        return true;
    }
}
