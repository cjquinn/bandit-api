<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

use DateTime;

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
                'created' => new DateTime('yesterday'),
                'modified' => new DateTime('yesterday')
            ]
        ];

        parent::init();
    }
}
