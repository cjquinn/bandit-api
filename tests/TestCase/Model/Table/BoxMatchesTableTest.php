<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class BoxMatchesTableTest extends TestCase
{

    public $fixtures = [
        'app.box_matches'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->BoxMatches = TableRegistry::get('BoxMatches');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BoxMatches);

        parent::tearDown();
    }
}
