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
                'rating' => 1169,
                'name' => 'Christy Quinn'
            ],
            [
                'id' => 2,
                'login_id' => 2,
                'rating' => 1231,
                'name' => 'Russell Bishop'
            ],
            [
                'id' => 3,
                'login_id' => 3,
                'rating' => 1200,
                'name' => 'Tom Lippitt'
            ]
        ];

        parent::init();
    }
}
