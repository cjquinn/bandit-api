<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class LoginsFixture extends TestFixture
{

    public $import = [
        'table' => 'logins'
    ];

    public function init()
    {
    	$this->records = [
    		[
		    	'id' => 1,
		    	'email' => 'christy@bandit.localhost',
		    	'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
    		]
	    ];

	    parent::init();
    }
}