<?php

namespace App\Test\Fixture;

use Cake\I18n\Time;
use Cake\TestSuite\Fixture\TestFixture;

class MatchesFixture extends TestFixture
{
    public $import = [
        'table' => 'matches'
    ];

    public function init()
    {
        $this->records = [
            // 4 days ago
            [
                'id' => 1,
                'club_id' => 1,
                'player_a_id' => 1,
                'player_b_id' => 2,
                'player_a_score' => 1,
                'player_b_score' => 0,
                'created' => new Time('4 days ago'),
                'modified' => new Time('4 days ago')
            ],
            // 3 days ago
            [
                'id' => 2,
                'club_id' => 1,
                'player_a_id' => 1,
                'player_b_id' => 2,
                'player_a_score' => 1,
                'player_b_score' => 0,
                'created' => new Time('3 days ago'),
                'modified' => new Time('3 days ago')
            ],
            // 2 days ago
            [
                'id' => 3,
                'club_id' => 1,
                'player_a_id' => 2,
                'player_b_id' => 3,
                'player_a_score' => 1,
                'player_b_score' => 0,
                'created' => new Time('2 days ago'),
                'modified' => new Time('2 days ago')
            ],
            [
                'id' => 4,
                'club_id' => 1,
                'player_a_id' => 4,
                'player_b_id' => 5,
                'player_a_score' => 1,
                'player_b_score' => 0,
                'created' => new Time('2 days ago'),
                'modified' => new Time('2 days ago')
            ],
            // 1 day ago
            [
                'id' => 5,
                'club_id' => 1,
                'player_a_id' => 5,
                'player_b_id' => 1,
                'player_a_score' => 1,
                'player_b_score' => 0,
                'created' => new Time('1 day ago'),
                'modified' => new Time('1 day ago')
            ],
            [
                'id' => 6,
                'club_id' => 1,
                'player_a_id' => 6,
                'player_b_id' => 7,
                'player_a_score' => 1,
                'player_b_score' => 0,
                'created' => new Time('1 day ago'),
                'modified' => new Time('1 day ago')
            ],
            // Today
            [
                'id' => 7,
                'club_id' => 1,
                'player_a_id' => 4,
                'player_b_id' => 2,
                'player_a_score' => 1,
                'player_b_score' => 0,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ],
            [
                'id' => 8,
                'club_id' => 1,
                'player_a_id' => 6,
                'player_b_id' => 7,
                'player_a_score' => 1,
                'player_b_score' => 0,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ],
            [
                'id' => 9,
                'club_id' => 2,
                'player_a_id' => 8,
                'player_b_id' => 1,
                'player_a_score' => 1,
                'player_b_score' => 0,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ]
        ];

        parent::init();
    }
}
