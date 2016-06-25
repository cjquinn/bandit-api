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

        $this->assertNotNull($boxMatch->losing_player_results);
        $this->assertNotNull($boxMatch->winning_player_results);

        $this->assertEquals(count($boxMatch->losing_player_results), 2);
        $this->assertEquals(count($boxMatch->winning_player_results), 3);

        $boxMatch = $this->BoxMatches->find()
            ->contain([
                'LosingPlayerResults',
                'WinningPlayerResults'
            ])
            ->where([
                'BoxMatches.box_id' => 1,
                'BoxMatches.losing_player_id' => 1,
                'BoxMatches.winning_player_id' => 4
            ]);

        $boxMatch = $this->BoxMatches->get([
            'box_id' => 1,
            'losing_player_id' => 1,
            'winning_player_id' => 4
        ], [
            'contain' => [
                'LosingPlayerResults',
                'WinningPlayerResults'
            ]
        ]);

        $this->assertNotNull($boxMatch->losing_player_results);
        $this->assertNotNull($boxMatch->winning_player_results);

        $this->assertEquals(count($boxMatch->losing_player_results), 2);
        $this->assertEquals(count($boxMatch->winning_player_results), 3);
    }
}
