<?php

namespace App\Test\Fixture;

use Cake\Auth\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{

    public $import = [
        'table' => 'users'
    ];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Christy',
                'reputation' => 3,
                'email' => 'christy@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'name' => 'Russell',
                'reputation' => 4,
                'email' => 'russell@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'name' => 'Nathan',
                'reputation' => 1,
                'email' => 'nathan@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'Tom',
                'reputation' => 2,
                'email' => 'tom@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'name' => 'Alex',
                'reputation' => 1,
                'email' => 'alex@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'name' => 'Sam',
                'reputation' => 2,
                'email' => 'sam@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'name' => 'Dom',
                'reputation' => 2,
                'email' => 'dom@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 8,
                'name' => 'Gareth',
                'reputation' => 0,
                'email' => 'gareth@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
