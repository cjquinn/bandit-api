<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class DisputesFixture extends TestFixture
{

    public $import = [
        'table' => 'disputes'
    ];

    public function init()
    {
        $this->records = [
            [
                'player_id' => 3,
                'result_id' => 3,
                'message' => null,
                'is_resolved' => null
            ]
        ];

        parent::init();
    }
}
