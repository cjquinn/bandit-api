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
            ],
            [
                'id' => 2,
                'losing_player_id' => 1,
                'winning_player_id' => 3,
                'created' => new DateTime('25 hours ago'),
                'modified' => new DateTime('25 hours ago')
            ],
            [
                'id' => 3,
                'losing_player_id' => 1,
                'winning_player_id' => 2,
                'created' => new DateTime('today'),
                'modified' => new DateTime('today')
            ]
        ];

        parent::init();
    }
}
