<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

use DateTime;

class BoxLeagueCyclesFixture extends TestFixture
{

    public $import = [
        'table' => 'box_league_cycles'
    ];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'club_id' => 1,
                'start' => new DateTime('-2 weeks'),
                'end' => new DateTime('+2 weeks')
            ]
        ];

        parent::init();
    }
}
