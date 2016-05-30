<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BoxesPlayersFixture extends TestFixture
{

    public $import = [
        'table' => 'boxes_players'
    ];

    public function init()
    {
        $this->records = [
            [
                'box_id' => 1,
                'box_league_cycle_id' => 1,
                'player_id' => 1,
                'points' => 0
            ],
            [
                'box_id' => 1,
                'box_league_cycle_id' => 1,
                'player_id' => 2,
                'points' => 0
            ],
            [
                'box_id' => 1,
                'box_league_cycle_id' => 1,
                'player_id' => 3,
                'points' => 0
            ],
            [
                'box_id' => 1,
                'box_league_cycle_id' => 1,
                'player_id' => 4,
                'points' => 0
            ],
            [
                'box_id' => 2,
                'box_league_cycle_id' => 1,
                'player_id' => 5,
                'points' => 0
            ],
            [
                'box_id' => 2,
                'box_league_cycle_id' => 1,
                'player_id' => 6,
                'points' => 0
            ],
            [
                'box_id' => 2,
                'box_league_cycle_id' => 1,
                'player_id' => 7,
                'points' => 0
            ],
            [
                'box_id' => 2,
                'box_league_cycle_id' => 1,
                'player_id' => 8,
                'points' => 0
            ]
        ];

        parent::init();
    }
}
