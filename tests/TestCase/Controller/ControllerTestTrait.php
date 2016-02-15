<?php

namespace App\Test\TestCase\Controller;

trait ControllerTestTrait
{

    public $fixtures = [
        'app.disputes',
        'app.histories',
        'app.logins',
        'app.players',
        'app.results'
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
                    'id' => $id,
                    'player' => [
                        'id' => $id
                    ]
                ]
            ]
        ]);
    }

    /**
     * @return void
     */
    private function _setAjaxRequest()
    {
        $_ENV['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);
    }
}
