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
                'current_club_id' => 1,
                'login_id' => 1,
                'name' => 'Christy Quinn',
                'reputation' => 3
            ],
            [
                'id' => 2,
                'current_club_id' => 1,
                'login_id' => 2,
                'name' => 'Russell Bishop',
                'reputation' => 2
            ],
            [
                'id' => 3,
                'current_club_id' => 1,
                'login_id' => 3,
                'name' => 'Tom Lippitt',
                'reputation' => 1
            ]
        ];

        parent::init();
    }
}
