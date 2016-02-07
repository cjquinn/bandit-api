<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class HistoriesFixture extends TestFixture
{

    public $import = [
        'table' => 'histories'
    ];

    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
