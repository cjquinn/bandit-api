<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ResultsTableTest extends TestCase
{

    public $fixtures = [
        'app.box_matches',
        'app.clubs',
        'app.clubs_players',
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
    public function testDelete()
    {
        $result = $this->Results->get(2);

        $this->Results->delete($result);

        $this->assertFalse($this->Results->exists([
            'id' => 2
        ]));

        $christy = $this->Results->Players
            ->findById(1)
            ->find('club', [
                'clubId' => 1
            ])
            ->firstOrFail();
        $russell = $this->Results->Players
            ->findById(2)
            ->find('club', [
                'clubId' => 1
            ])
            ->firstOrFail();
        $tom = $this->Results->Players
            ->findById(3)
            ->find('club', [
                'clubId' => 1
            ])
            ->firstOrFail();

        $this->assertEquals(1169, $christy->club->rating);
        $this->assertEquals(2, $christy->club->losses);
        $this->assertEquals(0, $christy->club->wins);

        $this->assertEquals(1231, $russell->club->rating);
        $this->assertEquals(0, $russell->club->losses);
        $this->assertEquals(2, $russell->club->wins);

        $this->assertEquals(1200, $tom->club->rating);
        $this->assertEquals(0, $tom->club->losses);
        $this->assertEquals(0, $tom->club->wins);
    }
}
