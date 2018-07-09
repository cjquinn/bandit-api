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
                'first_name' => 'Christy',
                'last_name' => 'C',
                'reputation' => 3,
                'email' => 'christy@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'first_name' => 'Russell',
                'last_name' => 'R',
                'reputation' => 4,
                'email' => 'russell@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'first_name' => 'Nathan',
                'last_name' => 'N',
                'reputation' => 1,
                'email' => 'nathan@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'first_name' => 'Tom',
                'last_name' => 'T',
                'reputation' => 2,
                'email' => 'tom@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'first_name' => 'Alex',
                'last_name' => 'A',
                'reputation' => 1,
                'email' => 'alex@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'first_name' => 'Sam',
                'last_name' => 'S',
                'reputation' => 2,
                'email' => 'sam@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'first_name' => 'Dom',
                'last_name' => 'D',
                'reputation' => 2,
                'email' => 'dom@banditmatch.com',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 8,
                'first_name' => 'Gareth',
                'last_name' => 'G',
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
