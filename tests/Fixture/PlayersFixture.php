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
                'rating' => 1154,
                'reputation' => 3
            ],
            [
                'id' => 2,
                'login_id' => 2,
                'name' => 'Russell Bishop',
                'rating' => 1230,
                'reputation' => 2
            ],
            [
                'id' => 3,
                'login_id' => 3,
                'name' => 'Tom Lippitt',
                'rating' => 1216,
                'reputation' => 1
            ]
        ];

        parent::init();
    }
}
