<?php

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;

trait ControllerTestTrait
{
    public $fixtures = [
        'app.Challenges',
        'app.Clubs',
        'app.Disputes',
        'app.Players',
        'app.Matches',
        'app.Snapshots',
        'app.Users'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $_ENV['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        if (!isset($this->_request['headers'])) {
            $this->_request['headers'] = [];
        }

        $this->_request['headers']['Accept'] = 'application/json';
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($_ENV['HTTP_X_REQUESTED_WITH']);
    }

    /**
     * Sets session up for Auth component
     *
     * @param $id The id of the login
     * @return void
     */
    private function _setAuthSession($id)
    {
        $token = $this->_table('Users')->generateJwt($id);

        if (!isset($this->_request['headers'])) {
            $this->_request['headers'] = [];
        }

        $this->_request['headers']['Authorization'] = 'Bearer ' . $token;
    }

    /**
     * @return \Cake\ORM\Table
     */
    private function _table($table)
    {
        return TableRegistry::get($table);
    }
}
