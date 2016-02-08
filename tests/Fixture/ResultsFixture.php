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
                'loser_id' => 2,
                'winner_id' => 1,
                'date' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
