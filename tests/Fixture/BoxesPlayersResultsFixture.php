<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BoxesPlayersResultsFixture extends TestFixture
{

    public $import = [
        'table' => 'boxes_players_results'
    ];

    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
