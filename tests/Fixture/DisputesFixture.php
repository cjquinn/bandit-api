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
                'id' => 1,
                'result_id' => 1,
                'player_a_score' => null,
                'player_b_score' => null,
                'is_resolved' => null,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
