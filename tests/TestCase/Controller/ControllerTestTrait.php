<?php

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;

trait ControllerTestTrait
{

    public $fixtures = [
        'app.clubs',
        'app.disputes',
        'app.players',
        'app.results',
        'app.users'
    ];

    /**
     * Sets session up for Auth component
     *
     * @param $id The id of the user
     * @return void
     */
    private function _setAuthSession($id)
    {
        $token = TableRegistry::get('Users')->generateToken($id);

        if (!isset($this->_request['headers'])) {
            $this->_request['headers'] = [];
        }

        $this->_request['headers']['Authorization'] = 'Bearer ' . $token;
    }

    /**
     * @return void
     */
    private function _setAjaxRequest()
    {
        $_ENV['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        if (!isset($this->_request['headers'])) {
            $this->_request['headers'] = [];
        }

        $this->_request['headers']['Accept'] = 'application/json';
    }
}
