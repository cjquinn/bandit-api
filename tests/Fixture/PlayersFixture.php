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
                'rating' => 1154,
                'name' => 'Christy Quinn'
            ],
            [
                'id' => 2,
                'login_id' => 2,
                'rating' => 1230,
                'name' => 'Russell Bishop'
            ],
            [
                'id' => 3,
                'login_id' => 3,
                'rating' => 1216,
                'name' => 'Tom Lippitt'
            ]
        ];

        parent::init();
    }
}
