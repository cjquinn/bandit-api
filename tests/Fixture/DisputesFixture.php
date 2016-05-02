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
                'result_id' => 2,
                'message' => null,
                'is_resolved' => null
            ]
        ];

        parent::init();
    }
}
