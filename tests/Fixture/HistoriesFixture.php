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
                'difference' => -32,
                'rating' => 1168
            ],
            [
                'player_id' => 2,
                'result_id' => 1,
                'difference' => 32,
                'rating' => 1232
            ]
        ];

        parent::init();
    }
}
