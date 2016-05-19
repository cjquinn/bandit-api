<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BoxesFixture extends TestFixture
{

    public $import = [
        'table' => 'boxes'
    ];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'box_league_cycle_id' => 1,
                'division' => 1
            ]
        ];

        parent::init();
    }
}
