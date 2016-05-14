<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BoxLeaguesFixture extends TestFixture
{

    public $import = [
        'table' => 'box_leagues'
    ];

    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
