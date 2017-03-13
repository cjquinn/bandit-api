<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PlayersFixture extends TestFixture
{

    public $import = [
        'table' => 'players'
    ];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'login_id' => 1,
                'name' => 'Christy Quinn',
                'reputation' => 3
            ],
            [
                'id' => 2,
                'login_id' => 2,
                'name' => 'Russell Bishop',
                'reputation' => 2
            ],
            [
                'id' => 3,
                'login_id' => 3,
                'name' => 'Tom Lippitt',
                'reputation' => 1
            ],
            [
                'id' => 4,
                'name' => 'Bob Fellows',
                'reputation' => 0
            ],
            [
                'id' => 5,
                'name' => 'Alex Day',
                'reputation' => 0
            ],
            [
                'id' => 6,
                'name' => 'Sam Kind',
                'reputation' => 0
            ],
            [
                'id' => 7,
                'name' => 'Rodger Bally',
                'reputation' => 0
            ],
            [
                'id' => 8,
                'name' => 'Tommy Castle',
                'reputation' => 0
            ]
        ];

        parent::init();
    }
}
