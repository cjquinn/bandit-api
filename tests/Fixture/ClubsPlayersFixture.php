<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ClubsPlayersFixture extends TestFixture
{

    public $import = [
        'table' => 'clubs_players'
    ];

    public function init()
    {
        $this->records = [
            [
                'club_id' => 1,
                'player_id' => 1,
                'losses' => 3,
                'rating' => 1154,
                'wins' => 0
            ],
            [
                'club_id' => 1,
                'player_id' => 2,
                'losses' => 0,
                'rating' => 1230,
                'wins' => 2
            ],
            [
                'club_id' => 1,
                'player_id' => 3,
                'losses' => 0,
                'rating' => 1216,
                'wins' => 1
            ],
            [
                'club_id' => 2,
                'player_id' => 1,
                'losses' => 0,
                'rating' => 1200,
                'wins' => 0,
            ],
            [
                'club_id' => 2,
                'player_id' => 3,
                'losses' => 0,
                'rating' => 1200,
                'wins' => 0,
            ]
        ];

        parent::init();
    }
}
