<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class BoxMatchesTableTest extends TestCase
{

    public $fixtures = [
        'app.box_league_cycles',
        'app.box_matches',
        'app.boxes',
        'app.boxes_players',
        'app.clubs',
        'app.clubs_players',
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
        $this->BoxMatches = TableRegistry::get('BoxMatches');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->BoxMatches);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testValidationDefault()
    {
        $errors = $this->BoxMatches->validator()->errors([
            'losing_player_id' => 1,
            'losses' => -1,
            'wins' => 0
        ]);

        $expected = [
            'losses' => [
                'nonNegativeInteger' => 'The provided value is invalid'
            ],
            'wins' => [
                'greaterThanOrEqual' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($expected, $errors);

        $errors = $this->BoxMatches->validator()->errors([
            'losing_player_id' => 1,
            'losses' => 3,
            'wins' => 5
        ]);

        $expected = [
            'losses' => [
                'lessThanOrEqual' => 'The provided value is invalid'
            ],
            'wins' => [
                'lessThanOrEqual' => 'The provided value is invalid'
            ],
        ];

        $this->assertEquals($expected, $errors);

        $errors = $this->BoxMatches->validator()->errors([
            'losing_player_id' => 1,
            'losses' => 2,
            'wins' => 1
        ]);

        $expected = [
            'losses' => [
                'valid' => 'You must enter more wins then losses'
            ],
            'wins' => [
                'valid' => 'You must enter more wins then losses'
            ],
        ];

        $this->assertEquals($expected, $errors);
    }

    /**
     * @return void
     */
    public function testBeforeSave()
    {
        $boxMatch = $this->BoxMatches->newEntity([
            'losing_player_id' => 1,
            'losses' => 2,
            'wins' => 3
        ]);

        $boxMatch->set('box_id', 1);
        $boxMatch->set('winning_player_id', 4);

        $this->BoxMatches->save($boxMatch);

        $this->assertNotNull($boxMatch->results);
        $this->assertEquals(count($boxMatch->results), 5);

        $boxMatch = $this->BoxMatches->get($boxMatch->id, [
            'contain' => [
                'LosingBoxesPlayers',
                'WinningBoxesPlayers'
            ]
        ]);

        $this->assertEquals($boxMatch->losing_boxes_player->points, 4);
        $this->assertEquals($boxMatch->winning_boxes_player->points, 4);

        $totalLosingPlayerResults = $this->BoxMatches->Results
            ->findByBoxMatchId($boxMatch->id)
            ->where([
                'winning_player_id' => 1
            ])
            ->count();

        $this->assertEquals(2, $totalLosingPlayerResults);

        $totalWinningPlayerResults = $this->BoxMatches->Results
            ->findByBoxMatchId($boxMatch->id)
            ->where([
                'winning_player_id' => 4
            ])
            ->count();

        $this->assertEquals(3, $totalWinningPlayerResults);
    }

    /**
     * @return void
     */
    public function testLosingPlayerPoints()
    {
        $losingPlayerPoints = $this->BoxMatches->losingPlayerPoints([
            'wins' => 3,
            'losses' => 0
        ]);

        $this->assertEquals($losingPlayerPoints, 1);

        $losingPlayerPoints = $this->BoxMatches->losingPlayerPoints([
            'wins' => 3,
            'losses' => 1
        ]);

        $this->assertEquals($losingPlayerPoints, 2);

        $losingPlayerPoints = $this->BoxMatches->losingPlayerPoints([
            'wins' => 3,
            'losses' => 2
        ]);

        $this->assertEquals($losingPlayerPoints, 3);

        $losingPlayerPoints = $this->BoxMatches->losingPlayerPoints([
            'wins' => 2,
            'losses' => 0
        ]);

        $this->assertEquals($losingPlayerPoints, 1);

        $losingPlayerPoints = $this->BoxMatches->losingPlayerPoints([
            'wins' => 2,
            'losses' => 1
        ]);

        $this->assertEquals($losingPlayerPoints, 2);

        $losingPlayerPoints = $this->BoxMatches->losingPlayerPoints([
            'wins' => 2,
            'losses' => 2
        ]);

        $this->assertEquals($losingPlayerPoints, 3);

        $losingPlayerPoints = $this->BoxMatches->losingPlayerPoints([
            'wins' => 1,
            'losses' => 0
        ]);

        $this->assertEquals($losingPlayerPoints, 1);

        $losingPlayerPoints = $this->BoxMatches->losingPlayerPoints([
            'wins' => 1,
            'losses' => 1
        ]);

        $this->assertEquals($losingPlayerPoints, 2);

        $losingPlayerPoints = $this->BoxMatches->losingPlayerPoints([
            'wins' => 0,
            'losses' => 0
        ]);

        $this->assertEquals($losingPlayerPoints, 1);
    }

    /**
     * @return void
     */
    public function testWinningPlayerPoints()
    {
        $winningPlayerPoints = $this->BoxMatches->winningPlayerPoints([
            'wins' => 3,
            'losses' => 0
        ]);

        $this->assertEquals($winningPlayerPoints, 6);

        $winningPlayerPoints = $this->BoxMatches->winningPlayerPoints([
            'wins' => 3,
            'losses' => 1
        ]);

        $this->assertEquals($winningPlayerPoints, 5);

        $winningPlayerPoints = $this->BoxMatches->winningPlayerPoints([
            'wins' => 3,
            'losses' => 2
        ]);

        $this->assertEquals($winningPlayerPoints, 4);

        $winningPlayerPoints = $this->BoxMatches->winningPlayerPoints([
            'wins' => 2,
            'losses' => 0
        ]);

        $this->assertEquals($winningPlayerPoints, 3);

        $winningPlayerPoints = $this->BoxMatches->winningPlayerPoints([
            'wins' => 2,
            'losses' => 1
        ]);

        $this->assertEquals($winningPlayerPoints, 3);

        $winningPlayerPoints = $this->BoxMatches->winningPlayerPoints([
            'wins' => 2,
            'losses' => 2
        ]);

        $this->assertEquals($winningPlayerPoints, 3);

        $winningPlayerPoints = $this->BoxMatches->winningPlayerPoints([
            'wins' => 1,
            'losses' => 0
        ]);

        $this->assertEquals($winningPlayerPoints, 2);

        $winningPlayerPoints = $this->BoxMatches->winningPlayerPoints([
            'wins' => 1,
            'losses' => 1
        ]);

        $this->assertEquals($winningPlayerPoints, 2);

        $winningPlayerPoints = $this->BoxMatches->winningPlayerPoints([
            'wins' => 0,
            'losses' => 0
        ]);

        $this->assertEquals($winningPlayerPoints, 1);
    }
}
