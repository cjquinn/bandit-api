<?php

namespace App\Controller;

use Cake\Controller\Controller;

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
     * @return bool
     */
    public function isAuthorized($user)
    {
        return true;
    }
}
