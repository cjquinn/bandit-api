<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ClubsFixture extends TestFixture
{

    public $import = [
        'table' => 'clubs'
    ];

    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'founder_id' => 1,
                'name' => 'Squelch',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'founder_id' => 8,
                'name' => 'Ping Pong Game On',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        parent::init();
    }
}
