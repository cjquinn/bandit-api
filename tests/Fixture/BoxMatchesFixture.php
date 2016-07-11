<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

use DateTime;

class BoxMatchesFixture extends TestFixture
{

    public $import = [
        'table' => 'box_matches'
    ];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'box_id' => 1,
                'losing_player_id' => 1,
                'winning_player_id' => 2,
                'disputed' => null,
                'submitted' => new DateTime('yesterday')
            ]
        ];

        parent::init();
    }
}
