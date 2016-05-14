<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BoxesPlayersResultsFixture extends TestFixture
{

    public $import = [
        'table' => 'boxes_player_results'
    ];

    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
