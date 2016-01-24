<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class FilesFixture extends TestFixture
{

    public $import = [
        'table' => 'files'
    ];

    public function init()
    {
        $this->records = [];

        parent::init();
    }
}
