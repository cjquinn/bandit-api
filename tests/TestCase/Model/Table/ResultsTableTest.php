<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ResultsTableTest extends TestCase
{

    public $fixtures = [
        'app.clubs',
        'app.players',
        'app.results',
        'app.users'
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
    public function testBeforeSave()
    {
        $result = $this->Results->newEntity();

        $this->Results->patchEntity($result, [
            'player_b_id' => 1,
            'player_a_score' => 3,
            'player_b_score' => 0
        ]);

        $result->set('club_id', 1);
        $result->set('player_a_id', 1);

        // Can't add a result against yourself
        $this->assertFalse($this->Results->save($result));

        $result->set('player_a_id', 2);

        $this->Results->save($result);

        // Snapshots should be set
        $this->assertTrue($this->Results->save($result) !== false);
        $this->assertTrue(is_array($result->player_a_snapshot));
        $this->assertTrue(is_array($result->player_b_snapshot));
    }

    /**
     * @return void
     */
    public function testFindTree()
    {
        $firstResult = $this->Results->get(2);

        $query = $this->Results->find('tree', [
            'result' => $firstResult
        ]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);

        $expected = [2, 3, 5, 7];
        $this->assertEquals($expected, $query->extract('id')->toArray());
    }

    /**
     * @return void
     */
    public function testIdTree()
    {
        $firstResult = $this->Results->get(2);

        $resultTree = $this->Results->idTree($firstResult);

        $expected = [
            2 => 2,
            3 => 3,
            7 => 7,
            5 => 5
        ];
        $this->assertEquals($expected, $resultTree);
    }
}
