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
                'rating' => 1200,
                'name' => 'Christy Quinn'
            ]
        ];

        parent::init();
    }
}
