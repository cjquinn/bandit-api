<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

use DateTime;

class ResultsFixture extends TestFixture
{

    public $import = [
        'table' => 'results'
    ];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'box_id' => 1,
                'club_id' => 1,
                'losing_player_id' => 1,
                'winning_player_id' => 2,
                'submitted' => new DateTime('yesterday')
            ],
            [
                'id' => 2,
                'box_id' => null,
                'club_id' => 1,
                'losing_player_id' => 1,
                'winning_player_id' => 3,
                'submitted' => new DateTime('25 hours ago')
            ],
            [
                'id' => 3,
                'box_id' => null,
                'club_id' => 1,
                'losing_player_id' => 1,
                'winning_player_id' => 2,
                'submitted' => new DateTime('today')
            ]
        ];

        parent::init();
    }
}
