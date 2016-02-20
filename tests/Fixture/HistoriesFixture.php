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
                'difference' => -16,
                'rating' => 1184
            ],
            [
                'player_id' => 2,
                'result_id' => 1,
                'difference' => 16,
                'rating' => 1216
            ],
            [
                'player_id' => 1,
                'result_id' => 2,
                'difference' => -16,
                'rating' => 1168
            ],
            [
                'player_id' => 3,
                'result_id' => 2,
                'difference' => 16,
                'rating' => 1216
            ],
            [
                'player_id' => 1,
                'result_id' => 3,
                'difference' => -15,
                'rating' => 1153
            ],
            [
                'player_id' => 2,
                'result_id' => 3,
                'difference' => 15,
                'rating' => 1231
            ]
        ];

        parent::init();
    }
}
