<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ClubsFixture extends TestFixture
{

    public $import = [
        'table' => 'clubs'
    ];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'founding_player_id' => 1,
                'name' => 'Squelch'
            ],
            [
                'id' => 2,
                'founding_player_id' => 1,
                'name' => 'Ping Pong'
            ]
        ];

        parent::init();
    }
}
