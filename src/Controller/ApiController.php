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
    public function isAuthorised($user)
    {
        if (!$this->request->is('ajax') ||
            !$this->request->is('json')
        ) {
            return false;
        }

        return parent::isAuthorised($user);
    }
}