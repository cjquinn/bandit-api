<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class HistoriesFixture extends TestFixture
{

    public $import = [
        'table' => 'histories'
    ];

    public function init()
    {
        $this->records = [
            [
                'player_id' => 1,
                'result_id' => 1,
                'snapshot' => json_encode([
                    'difference' => -16,
                    'losses' => 1,
                    'rating' => 1184,
                    'wins' => 0
                ]),
                'is_winner' => false
            ],
            [
                'player_id' => 2,
                'result_id' => 1,
                'snapshot' => json_encode([
                    'difference' => 16,
                    'losses' => 0,
                    'rating' => 1216,
                    'wins' => 1
                ]),
                'is_winner' => true
            ],
            [
                'player_id' => 1,
                'result_id' => 2,
                'snapshot' => json_encode([
                    'difference' => -16,
                    'losses' => 2,
                    'rating' => 1168,
                    'wins' => 0
                ]),
                'is_winner' => false
            ],
            [
                'player_id' => 3,
                'result_id' => 2,
                'snapshot' => json_encode([
                    'difference' => 16,
                    'losses' => 0,
                    'rating' => 1216,
                    'wins' => 1
                ]),
                'is_winner' => true
            ],
            [
                'player_id' => 1,
                'result_id' => 3,
                'snapshot' => json_encode([
                    'difference' => -14,
                    'losses' => 3,
                    'rating' => 1154,
                    'wins' => 0
                ]),
                'is_winner' => false
            ],
            [
                'player_id' => 2,
                'result_id' => 3,
                'snapshot' => json_encode([
                    'difference' => 14,
                    'losses' => 0,
                    'rating' => 1230,
                    'wins' => 2
                ]),
                'is_winner' => true
            ]
        ];

        parent::init();
    }
}
