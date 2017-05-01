<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ClubsTableTest extends TestCase
{

    public $fixtures = [];

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
    public function testPatchEntityNewUser()
    {
        $club = $this->Clubs->newEntity();

        $this->Clubs->patchEntityNewUser($club, []);

        $expected = [
            'name' => [
                '_required' => 'This field is required'
            ],
            'founder' => [
                '_required' => 'This field is required'
            ]
        ];
        $this->assertEquals($expected, $club->errors());

        $this->Clubs->patchEntityNewUser($club, [
            'name' => 'Ping Pong Game On',
            'founder' => [
                'name' => 'Alex Farthing',
                'email' => 'alex@gmail.com',
                'password' => 'password'
            ]
        ]);

        $this->assertEmpty($club->errors());
        $this->assertNotNull($club->founder);
    }

    /**
     * @return void
     */
    public function testPatchEntityExistingUser()
    {
        $club = $this->Clubs->newEntity();

        $this->Clubs->patchEntityExistingUser($club, []);

        $expected = [
            'name' => [
                '_required' => 'This field is required'
            ],
            'founder_id' => [
                '_required' => 'This field is required'
            ]
        ];
        $this->assertEquals($expected, $club->errors());

        $this->Clubs->patchEntityExistingUser($club, [
            'name' => 'Ping Pong Game On',
            'founder_id' => 1
        ]);

        $this->assertEmpty($club->errors());
        $this->assertNotNull($club->founder_id);
    }
}
