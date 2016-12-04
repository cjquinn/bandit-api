<?php

namespace App\Controller;

use Cake\Controller\Controller;

class TemplatesController extends Controller
{

    /**
     * @return void
     */
    public function display($template)
    {
        $this->viewBuilder()->template($template);
    }

    /**
     * @return void
     */
    public function login()
    {
        $this->viewBuilder()->layout('login');
    }

    /**
     * @return void
     */
    public function onboarding()
    {
        $this->viewBuilder()->layout('onboarding');
    }
}
