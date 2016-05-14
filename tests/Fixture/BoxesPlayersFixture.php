<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BoxesPlayersFixture extends TestFixture
{

    public $import = [
        'table' => 'boxes_players'
    ];

    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
