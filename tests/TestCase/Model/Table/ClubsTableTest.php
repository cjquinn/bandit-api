<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ClubsTableTest extends TestCase
{

    public $fixtures = [
        'app.clubs',
        'app.clubs_players',
        'app.logins',
        'app.players',
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Clubs = TableRegistry::get('Clubs');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Clubs);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testValidation()
    {
        // founding_player_id must be set if founding_player isn't set
        $errors = $this->Clubs->validator()->errors([
            'name' => 'Ping Pong Game On'
        ]);

        $expected = [
            'founding_player' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($errors, $expected);

        // founding_player is not required is an exisiting player_id is supplied
        $errors = $this->Clubs->validator()->errors([
            'name' => 'Ping Pong Game On',
            'founding_player_id' => 1
        ]);

        $expected = [];

        $this->assertEquals($errors, $expected);
    }
}
