<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BoxMatchesFixture extends TestFixture
{

    public $import = [
        'table' => 'box_matches'
    ];

    public function init()
    {
        $this->records = [
            [
                'box_id' => 1,
                'losing_player_id' => 1,
                'winning_player_id' => 2,
                'disputed' => null
            ]
        ];

        parent::init();
    }
}
