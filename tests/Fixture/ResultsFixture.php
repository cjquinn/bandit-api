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
        $this->records = [];

        parent::init();
    }
}
