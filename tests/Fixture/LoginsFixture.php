<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class LoginsFixture extends TestFixture
{

    public $import = [
        'table' => 'logins'
    ];

    public $records = [
        [
            'id' => 1,
            'email' => 'christyjquinn@gmail.com',
            'password' => 'password',
            'token' => null,
            'token_sent' => null,
            'created' => '2015-10-19 12:26:09',
            'modified' => '2015-10-19 12:26:09'
        ]
    ];
}