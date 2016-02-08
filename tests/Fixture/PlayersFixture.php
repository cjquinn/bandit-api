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
                'rating' => 1168,
                'name' => 'Christy Quinn'
            ],
            [
                'id' => 2,
                'login_id' => 2,
                'rating' => 1200,
                'name' => 'Russell Bishop'
            ]
        ];

        parent::init();
    }
}
