<?php

namespace App\Test\TestCase\Controller;

trait ControllerTestTrait
{

    public $fixtures = [
        'app.logins'
    ];

    /**
     * Sets session up for Auth component
     *
     * @param $id The id of the login
     * @return void
     */
    private function _setAuthSession($id)
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => $id
                ]
            ]
        ]);
    }
}