<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ResultsTableTest extends TestCase
{

    public $fixtures = [
        'app.disputes',
        'app.histories',
        'app.logins',
        'app.players',
        'app.results'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Results = TableRegistry::get('Results');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Results);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testNullify()
    {
        $result = $this->Results->get(2);

        $this->Results->nullify($result);

        $christy = $this->Results->Players->get(1);
        $russell = $this->Results->Players->get(2);
        $tom = $this->Results->Players->get(3);

        $this->assertEquals(1170, $christy->rating);
        $this->assertEquals(1230, $russell->rating);
        $this->assertEquals(1200, $tom->rating);
    }
}
