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
                'action' => 'login',
                '_ext' => 'json',
                '_method' => 'POST'
            ]
        ]);
    }

    /**
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow('display');
    }

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        return true;
    }

    /**
     * @return void
     */
    final public function display()
    {
    }
}
