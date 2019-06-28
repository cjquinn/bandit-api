<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ChallengesFixture extends TestFixture
{
    public $import = [
        'table' => 'challenges'
    ];

    public function init()
    {
        $this->records = [
            // 1 - not acceppted club 1, player_a_id 1
            [
                'id' => 1,
                'club_id' => 1,
                'match_id' => null,
                'player_a_id' => 1,
                'player_b_id' => null,
                'location' => 'Somewhere',
                'match_datetime' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'created' => date('Y-m-d H:i:s'),
                'deleted' => null,
                'modified' => date('Y-m-d H:i:s')
            ],
            // 2 - not accepted club 2, player_a_id 8
            [
                'id' => 2,
                'club_id' => 2,
                'match_id' => null,
                'player_a_id' => 8,
                'player_b_id' => null,
                'location' => 'Somewhere',
                'match_datetime' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'created' => date('Y-m-d H:i:s'),
                'deleted' => null,
                'modified' => date('Y-m-d H:i:s')
            ],
            // 3 - accepted club 1, player_a_id 2, player_b_id 3
            [
                'id' => 3,
                'club_id' => 1,
                'match_id' => null,
                'player_a_id' => 2,
                'player_b_id' => 3,
                'location' => 'Somewhere',
                'match_datetime' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'created' => date('Y-m-d H:i:s'),
                'deleted' => null,
                'modified' => date('Y-m-d H:i:s')
            ],
            // 4 - not accepted club 2, player_a_id 9
            [
                'id' => 4,
                'club_id' => 2,
                'match_id' => null,
                'player_a_id' => 9,
                'player_b_id' => null,
                'location' => 'Somewhere',
                'match_datetime' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'created' => date('Y-m-d H:i:s'),
                'deleted' => null,
                'modified' => date('Y-m-d H:i:s')
            ],
            // 5 - accepted club 1, player_a_id 1, player_b_id 2, match_id 1
            [
                'id' => 5,
                'club_id' => 1,
                'match_id' => 1,
                'player_a_id' => 1,
                'player_b_id' => 2,
                'location' => 'Somewhere',
                'match_datetime' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'created' => date('Y-m-d H:i:s'),
                'deleted' => null,
                'modified' => date('Y-m-d H:i:s')
            ],
            // 6 - deleted club 1, player_a_id, 2
            [
                'id' => 6,
                'club_id' => 1,
                'match_id' => null,
                'player_a_id' => 2,
                'player_b_id' => null,
                'location' => 'Somewhere',
                'match_datetime' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'created' => date('Y-m-d H:i:s'),
                'deleted' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            // 7 - accepted club 1, player_a_id 2, player_b_id 1
            [
                'id' => 7,
                'club_id' => 1,
                'match_id' => null,
                'player_a_id' => 2,
                'player_b_id' => 1,
                'location' => 'Somewhere',
                'match_datetime' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'created' => date('Y-m-d H:i:s'),
                'deleted' => null,
                'modified' => date('Y-m-d H:i:s')
            ],
            // 8 - deleted club 1, player_a_id, 2, player_b_id 1
            [
                'id' => 8,
                'club_id' => 1,
                'match_id' => null,
                'player_a_id' => 2,
                'player_b_id' => 1,
                'location' => 'Somewhere',
                'match_datetime' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'created' => date('Y-m-d H:i:s'),
                'deleted' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
        ];

        parent::init();
    }
}
