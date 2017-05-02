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
    public function testBeforeSaveInvalidPlayerB()
    {
        $result = $this->Results->newEntity();

        $this->Results->patchEntity($result, [
            // Can't add a result against yourself
            'player_b_id' => 1,
            'player_a_score' => 3,
            'player_b_score' => 0
        ]);

        $result->set('club_id', 1);
        $result->set('player_a_id', 1);

        $this->assertFalse($this->Results->save($result));

        $expected = [
            '_error' => [
                'invalid' => 'You cannot add results against yourself'
            ]
        ];
        $this->assertEquals($expected, $result->errors());
    }

    /**
     * @return void
     */
    public function testBeforeSaveUnassignedPlayerB()
    {
        $result = $this->Results->newEntity();

        $this->Results->patchEntity($result, [
            'player_b_id' => 8,
            'player_a_score' => 3,
            'player_b_score' => 0
        ]);

        $result->set('club_id', 1);
        $result->set('player_a_id', 1);

        $this->assertFalse($this->Results->save($result));

        $expected = [
            '_error' => [
                'invalid' => 'You can only add results against members of this club'
            ]
        ];
        $this->assertEquals($expected, $result->errors());
    }

    /**
     * @return void
     */
    public function testBeforeSaveSnapshots()
    {
        $result = $this->Results->newEntity();

        $this->Results->patchEntity($result, [
            'player_b_id' => 1,
            'player_a_score' => 3,
            'player_b_score' => 0
        ]);

        $result->set('club_id', 1);
        $result->set('player_a_id', 2);

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
