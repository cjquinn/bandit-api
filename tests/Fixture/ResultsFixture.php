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
                'losing_player_id' => 1,
                'winning_player_id' => 2,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
