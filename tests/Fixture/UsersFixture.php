<?php

namespace App\Test\Fixture;

use Cake\Core\Configure;
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
                'racketware_player_id' => null,
                'first_name' => 'Christy',
                'last_name' => 'C',
                'reputation' => 3,
                'email' => 'christy@banditmatch.com',
                'email_preferences' => Configure::read('Bandit.email_preferences'),
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'has_accepted_terms' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'racketware_player_id' => null,
                'first_name' => 'Russell',
                'last_name' => 'R',
                'reputation' => 4,
                'email' => 'russell@banditmatch.com',
                'email_preferences' => Configure::read('Bandit.email_preferences'),
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'has_accepted_terms' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'racketware_player_id' => null,
                'first_name' => 'Nathan',
                'last_name' => 'N',
                'reputation' => 1,
                'email' => 'nathan@banditmatch.com',
                'email_preferences' => Configure::read('Bandit.email_preferences'),
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'has_accepted_terms' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'racketware_player_id' => null,
                'first_name' => 'Tom',
                'last_name' => 'T',
                'reputation' => 2,
                'email' => 'tom@banditmatch.com',
                'email_preferences' => Configure::read('Bandit.email_preferences'),
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'has_accepted_terms' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'racketware_player_id' => null,
                'first_name' => 'Alex',
                'last_name' => 'A',
                'reputation' => 1,
                'email' => 'alex@banditmatch.com',
                'email_preferences' => Configure::read('Bandit.email_preferences'),
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'has_accepted_terms' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'racketware_player_id' => null,
                'first_name' => 'Sam',
                'last_name' => 'S',
                'reputation' => 2,
                'email' => 'sam@banditmatch.com',
                'email_preferences' => Configure::read('Bandit.email_preferences'),
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'has_accepted_terms' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'racketware_player_id' => null,
                'first_name' => 'Dom',
                'last_name' => 'D',
                'reputation' => 2,
                'email' => 'dom@banditmatch.com',
                'email_preferences' => Configure::read('Bandit.email_preferences'),
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'has_accepted_terms' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 8,
                'racketware_player_id' => null,
                'first_name' => 'Gareth',
                'last_name' => 'G',
                'reputation' => 0,
                'email' => 'gareth@banditmatch.com',
                'email_preferences' => Configure::read('Bandit.email_preferences'),
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'has_accepted_terms' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
