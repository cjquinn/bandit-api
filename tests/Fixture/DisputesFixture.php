<?php

namespace App\Test\Fixture;

use Cake\I18n\Time;
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
                'result_id' => 3,
                'player_a_score' => null,
                'player_b_score' => null,
                'is_resolved' => null,
                'created' => new Time('2 days ago'),
                'modified' => new Time('2 days ago')
            ]
        ];

        parent::init();
    }
}
