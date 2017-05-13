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
                'result_id' => 2,
                'player_a_score' => 2,
                'player_b_score' => 1,
                'is_resolved' => null,
                'created' => new Time('3 day ago'),
                'modified' => new Time('3 day ago')
            ],
            [
                'id' => 2,
                'result_id' => 5,
                'player_a_score' => 1,
                'player_b_score' => 0,
                'is_resolved' => true,
                'created' => new Time('1 day ago'),
                'modified' => new Time('1 day ago')
            ],
            [
                'id' => 3,
                'result_id' => 6,
                'player_a_score' => 2,
                'player_b_score' => 1,
                'is_resolved' => null,
                'created' => new Time('1 day ago'),
                'modified' => new Time('1 day ago')
            ]
        ];

        parent::init();
    }
}
