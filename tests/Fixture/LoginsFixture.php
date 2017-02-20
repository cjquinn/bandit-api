<?php

namespace App\Test\Fixture;

use Cake\Auth\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;

class LoginsFixture extends TestFixture
{

    public $import = [
        'table' => 'logins'
    ];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'email' => 'christy@bandit.localhost',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'email' => 'russell@bandit.localhost',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'email' => 'tom@bandit.localhost',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
