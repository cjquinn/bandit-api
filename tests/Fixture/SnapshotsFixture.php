<?php

namespace App\Test\Fixture;

use Cake\I18n\Time;
use Cake\TestSuite\Fixture\TestFixture;

class SnapshotsFixture extends TestFixture
{
    public $import = [
        'table' => 'snapshots'
    ];

    public function init()
    {
        $this->records = [
            [
                'match_id' => 1,
                'player_id' => 1,
                'racketware_session_id' => null,
                'rating' => 1220,
                'difference' => 20,
                'wins' => 1,
                'losses' => 0,
                'created' => new Time('4 days ago'),
                'modified' => new Time('4 days ago')
            ],
            [
                'match_id' => 1,
                'player_id' => 2,
                'racketware_session_id' => null,
                'rating' => 1180,
                'difference' => -20,
                'wins' => 0,
                'losses' => 1,
                'created' => new Time('4 days ago'),
                'modified' => new Time('4 days ago')
            ],
            [
                'match_id' => 2,
                'player_id' => 1,
                'racketware_session_id' => null,
                'rating' => 1238,
                'difference' => 18,
                'wins' => 2,
                'losses' => 0,
                'created' => new Time('3 days ago'),
                'modified' => new Time('3 days ago')
            ],
            [
                'match_id' => 2,
                'player_id' => 2,
                'racketware_session_id' => null,
                'rating' => 1162,
                'difference' => -18,
                'wins' => 0,
                'losses' => 2,
                'created' => new Time('3 days ago'),
                'modified' => new Time('3 days ago')
            ],
            [
                'match_id' => 3,
                'player_id' => 2,
                'racketware_session_id' => null,
                'rating' => 1184,
                'difference' => 22,
                'wins' => 1,
                'losses' => 2,
                'created' => new Time('2 days ago'),
                'modified' => new Time('2 days ago')
            ],
            [
                'match_id' => 3,
                'player_id' => 3,
                'racketware_session_id' => null,
                'rating' => 1178,
                'difference' => -22,
                'wins' => 0,
                'losses' => 1,
                'created' => new Time('2 days ago'),
                'modified' => new Time('2 days ago')
            ],
            [
                'match_id' => 4,
                'player_id' => 4,
                'racketware_session_id' => null,
                'rating' => 1220,
                'difference' => 20,
                'wins' => 1,
                'losses' => 0,
                'created' => new Time('2 days ago'),
                'modified' => new Time('2 days ago')
            ],
            [
                'match_id' => 4,
                'player_id' => 5,
                'racketware_session_id' => null,
                'rating' => 1180,
                'difference' => -20,
                'wins' => 0,
                'losses' => 1,
                'created' => new Time('2 days ago'),
                'modified' => new Time('2 days ago')
            ],
            [
                'match_id' => 5,
                'player_id' => 5,
                'racketware_session_id' => null,
                'rating' => 1203,
                'difference' => 23,
                'wins' => 1,
                'losses' => 1,
                'created' => new Time('1 day ago'),
                'modified' => new Time('1 day ago')
            ],
            [
                'match_id' => 5,
                'player_id' => 1,
                'racketware_session_id' => null,
                'rating' => 1215,
                'difference' => -23,
                'wins' => 2,
                'losses' => 1,
                'created' => new Time('1 day ago'),
                'modified' => new Time('1 day ago')
            ],
            [
                'match_id' => 6,
                'player_id' => 6,
                'racketware_session_id' => null,
                'rating' => 1220,
                'difference' => 20,
                'wins' => 1,
                'losses' => 0,
                'created' => new Time('1 day ago'),
                'modified' => new Time('1 day ago')
            ],
            [
                'match_id' => 6,
                'player_id' => 7,
                'racketware_session_id' => null,
                'rating' => 1180,
                'difference' => -20,
                'wins' => 0,
                'losses' => 1,
                'created' => new Time('1 day ago'),
                'modified' => new Time('1 day ago')
            ],
            [
                'match_id' => 7,
                'player_id' => 4,
                'racketware_session_id' => null,
                'rating' => 1238,
                'difference' => 18,
                'wins' => 2,
                'losses' => 0,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ],
            [
                'match_id' => 7,
                'player_id' => 2,
                'racketware_session_id' => null,
                'rating' => 1166,
                'difference' => -18,
                'wins' => 1,
                'losses' => 3,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ],
            [
                'match_id' => 8,
                'player_id' => 6,
                'racketware_session_id' => null,
                'rating' => 1238,
                'difference' => 18,
                'wins' => 2,
                'losses' => 0,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ],
            [
                'match_id' => 8,
                'player_id' => 7,
                'racketware_session_id' => null,
                'rating' => 1162,
                'difference' => -18,
                'wins' => 0,
                'losses' => 2,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ],
            [
                'match_id' => 9,
                'player_id' => 8,
                'racketware_session_id' => null,
                'rating' => 1220,
                'difference' => 20,
                'wins' => 1,
                'losses' => 0,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ],
            [
                'match_id' => 9,
                'player_id' => 9,
                'racketware_session_id' => null,
                'rating' => 1180,
                'difference' => -20,
                'wins' => 0,
                'losses' => 1,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ],
            [
                'match_id' => 10,
                'player_id' => 8,
                'racketware_session_id' => null,
                'rating' => 1200,
                'difference' => -20,
                'wins' => 1,
                'losses' => 1,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ],
            [
                'match_id' => 10,
                'player_id' => 9,
                'racketware_session_id' => null,
                'rating' => 1200,
                'difference' => 20,
                'wins' => 1,
                'losses' => 1,
                'created' => new Time('today'),
                'modified' => new Time('today')
            ],
        ];

        parent::init();
    }
}
