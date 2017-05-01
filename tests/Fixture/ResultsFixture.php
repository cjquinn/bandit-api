<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

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
                'club_id' => 1,
                'player_a_id' => 1,
                'player_b_id' => 2,
                'player_a_score' => 3,
                'player_b_score' => 1,
                'player_a_snapshot' => json_encode([
                    'rating' => 1220,
                    'difference' => 20,
                    'wins' => 1,
                    'losses' => 0
                ]),
                'player_b_snapshot' => json_encode([
                    'rating' => 1180,
                    'difference' => -20,
                    'wins' => 0,
                    'losses' => 1
                ]),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
