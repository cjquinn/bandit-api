<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BoxesFixture extends TestFixture
{

    public $import = [
        'table' => 'boxes'
    ];

    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
