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
                'email' => 'christy@bandit.play',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'name' => 'Russell',
                'reputation' => 4,
                'email' => 'russell@bandit.play',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'name' => 'Nathan',
                'reputation' => 1,
                'email' => 'nathan@bandit.play',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'Tom',
                'reputation' => 2,
                'email' => 'tom@bandit.play',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'name' => 'Alex',
                'reputation' => 1,
                'email' => 'alex@bandit.play',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'name' => 'Sam',
                'reputation' => 1,
                'email' => 'sam@bandit.play',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'name' => 'Dom',
                'reputation' => 1,
                'email' => 'dom@bandit.play',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 8,
                'name' => 'Gareth',
                'reputation' => 0,
                'email' => 'gareth@bandit.play',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
