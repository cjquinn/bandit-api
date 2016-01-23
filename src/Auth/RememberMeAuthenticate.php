<?php

namespace App\Auth;

use Cake\Auth\BaseAuthenticate;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Network\Response;

class RememberMeAuthenticate extends BaseAuthenticate
{

    /**
     * @return array|bool
     */
    public function authenticate(Request $request, Response $response)
    {
        return $this->getUser($request);
    }

    /**
     * @return array|bool
     */
    public function getUser(Request $request)
    {
        if (!$this->_registry->Cookie->check('remember_me')) {
            return false;
        }

        $cookie = $this->_registry->Cookie->read('remember_me');

        $this->config('fields.username', 'id');
        $user = $this->_findUser($cookie['id']);

        if ($user && !empty($cookie['userAgent']) && $request->header('User-Agent') === $cookie['userAgent']) {
            return $user;
        }

        return false;
    }
}