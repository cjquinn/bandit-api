<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

use DateTime;

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
    public function testBeforeDelete()
    {
        $result = $this->Results->get(2);

        $this->Results->delete($result);

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

    /**
     * @return void
     */
    public function testAfterDelete()
    {
        $result = $this->Results->get(2);

        $this->Results->delete($result);

        $losingPlayer = $this->Results->Players->get($result->losing_player_id);
        $winningPlayer = $this->Results->Players->get($result->winning_player_id);

        $this->assertEquals(2, $losingPlayer->reputation);
        $this->assertEquals(0, $winningPlayer->reputation);
    }

    /**
     * @return void
     * @group testing
     */
    public function testIdTree()
    {
        // Create some results
        $dates = [
            '49 hours ago' => [
                [
                    // 4
                    'losing_player_id' => 1,
                    'winning_player_id' => 2
                ]
            ],
            '48 hours ago' => [
                [
                    // 5
                    'losing_player_id' => 2,
                    'winning_player_id' => 3
                ],
                [
                    // 6
                    'losing_player_id' => 4,
                    'winning_player_id' => 5
                ]
            ],
            '1 day ago' => [
                [
                    // 7
                    'losing_player_id' => 5,
                    'winning_player_id' => 1
                ],
                [
                    // 8
                    'losing_player_id' => 6,
                    'winning_player_id' => 7
                ]
            ],
            'today' => [
                [
                    //9
                    'losing_player_id' => 4,
                    'winning_player_id' => 2
                ]
            ]
        ];

        $firstResult = null;

        foreach ($dates as $date => $results) {
            $date = new DateTime($date);

            foreach ($results as $data) {
                $result = $this->Results->newEntity();

                $result->set([
                    'club_id' => 2,
                    'losing_player_id' => $data['losing_player_id'],
                    'winning_player_id' => $data['winning_player_id'],
                    'submitted' => $date
                ], ['guard' => false]);

                $this->Results->save($result, [
                    'ignoreEvents' => true
                ]);

                if (!$firstResult) {
                    $firstResult = $result;
                }
            }
        }

        $resultTree = $this->Results->idTree($firstResult);

        $expected = [
            4 => 4,
            5 => 5,
            9 => 9,
            7 => 7
        ];
        $this->assertEquals($expected, $resultTree);
    }
}
