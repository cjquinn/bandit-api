<?php

namespace App\Test\Fixture;

use Cake\I18n\Time;
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
                'club_id' => 1,
                'user_id' => 1,
                'rating' => 1215,
                'wins' => 2,
                'losses' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'club_id' => 1,
                'user_id' => 2,
                'rating' => 1166,
                'wins' => 1,
                'losses' => 3,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'club_id' => 1,
                'user_id' => 3,
                'rating' => 1178,
                'wins' => 1,
                'losses' => 0,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'club_id' => 1,
                'user_id' => 4,
                'rating' => 1238,
                'wins' => 2,
                'losses' => 0,
                'created' => date('Y-m-d H:i:s'),
                'modified' => new Time('yesterday')
            ],
            [
                'id' => 5,
                'club_id' => 1,
                'user_id' => 5,
                'rating' => 1222,
                'wins' => 1,
                'losses' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'club_id' => 1,
                'user_id' => 6,
                'rating' => 1238,
                'wins' => 2,
                'losses' => 0,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'club_id' => 1,
                'user_id' => 7,
                'rating' => 1162,
                'wins' => 0,
                'losses' => 2,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 8,
                'club_id' => 2,
                'user_id' => 8,
                'rating' => 1200,
                'wins' => 0,
                'losses' => 0,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
