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
                'rating' => 1154
            ],
            [
                'club_id' => 1,
                'player_id' => 2,
                'rating' => 1230
            ],
            [
                'club_id' => 1,
                'player_id' => 3,
                'rating' => 1216
            ],
            [
                'club_id' => 2,
                'player_id' => 1,
                'rating' => 1200
            ]
        ];

        parent::init();
    }
}
