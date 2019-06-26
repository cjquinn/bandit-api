<?php

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChallengesTable;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ChallengesTableTest extends TestCase
{

    /**
     * @var \App\Model\Table\ChallengesTable
     */
    public $Challenges;

    /**
     * @var array
     */
    public $fixtures = [
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Challenges = TableRegistry::get('Challenges');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Challenges);

        parent::tearDown();
    }
}
