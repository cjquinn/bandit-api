<?php

namespace App\Test\TestCase\Model\Table;

use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ClubsTableTest extends TestCase
{
    public $fixtures = [
        'app.clubs',
        'app.players',
        'app.matches',
        'app.snapshots',
        'app.users'
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
    public function testPatchEntityAdd()
    {
        // Required
        $club = $this->Clubs->newEntity();
        $data = [];
        $user = ['id' => 1];

        $this->Clubs->patchEntityAdd($club, $data, $user);

        $expected = [
            'name' => [
                '_required' => 'This field is required'
            ],
            'founder' => [
                '_required' => 'This field is required'
            ],
            'founder_id' => [
                '_required' => 'This field is required'
            ]
        ];

        // Empty
        $club = $this->Clubs->newEntity();
        $data = [
            'name' => '',
            'founder' => '',
        ];
        $user = ['id' => ''];

        $this->Clubs->patchEntityAdd($club, $data, $user);

        $expected = [
            'name' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'founder' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'founder_id' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        // Valid new user
        $club = $this->Clubs->newEntity();
        $data = [
            'name' => 'Bandit',
            'founder' => [
                'name' => 'Alex Farthing',
                'email' => 'alex@gmail.com',
                'password' => 'password'
            ]
        ];
        $user = null;

        $this->Clubs->patchEntityAdd($club, $data, $user);

        $this->assertEmpty($club->getErrors());
        $this->assertNotNull($club->founder);
        $this->assertNotNull($club->founder->password);

        // Valid existing user
        $club = $this->Clubs->newEntity();
        $data = ['name' => 'Bandit'];
        $user = ['id' => 1];

        $this->Clubs->patchEntityAdd($club, $data, $user);

        $this->assertEmpty($club->getErrors());
        $this->assertEquals($club->founder_id, $user['id']);
    }

    /**
     * @return void
     */
    public function testPatchEntityEdit()
    {
        // Required
        $club = $this->Clubs->get(1);
        $data = [];

        $this->Clubs->patchEntityEdit($club, $data);

        $expected = [
            'name' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $club->getErrors());

        // Empty
        $club = $this->Clubs->get(1);
        $data = [
            'name' => ''
        ];

        $this->Clubs->patchEntityEdit($club, $data);

        $expected = [
            'name' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $club->getErrors());

        // Valid
        $club = $this->Clubs->get(1);
        $data = [
            'name' => 'Bandit'
        ];

        $this->Clubs->patchEntityEdit($club, $data);

        $this->assertEmpty($club->getErrors());
    }

    /**
     * @return void
     */
    public function testAfterSave()
    {
        $club = $this->Clubs->newEntity();
        $data = [
            'name' => 'Ping Pong Game On',
            'founder' => [
                'name' => 'Alex Farthing',
                'email' => 'alex@gmail.com',
                'password' => 'password'
            ]
        ];

        $this->Clubs->patchEntityAdd($club, $data, null);

        $this->Clubs->save($club);

        $this->assertTrue($this->Clubs->Players->exists([
            'club_id' => $club->id,
            'user_id' => $club->founder_id
        ]));

        $this->assertNotNull($club->founder);
        $this->assertNotEmpty($club->founder->players);
        $this->assertEquals(1, count($club->founder->players));
        $this->assertEquals($club->founder_id, $club->founder->players[0]->user_id);

        $club = $this->Clubs->newEntity();
        $data = ['name' => 'Ping Pong Game On'];

        $this->Clubs->patchEntityAdd($club, $data, ['id' => 1]);

        $this->Clubs->save($club);

        $this->assertTrue($this->Clubs->Players->exists([
            'club_id' => $club->id,
            'user_id' => $club->founder_id
        ]));

        $this->assertNotNull($club->founder);
        $this->assertNotEmpty($club->founder->players);
        $this->assertEquals(2, count($club->founder->players));
        $this->assertEquals($club->founder_id, $club->founder->players[0]->user_id);
        $this->assertEquals($club->founder_id, $club->founder->players[1]->user_id);
    }

    /**
     * @return void
     */
    public function testFindByUserId()
    {
        // Add player_id one to club 2
        $player = $this->Clubs->Players->newEntity();

        $player->set('club_id', 2);
        $player->set('user_id', 1);

        $this->Clubs->Players->save($player);

        // Tests
        $clubs = $this->Clubs->find('byUserId', ['userId' => 1]);

        $this->assertEquals(2, $clubs->count());
        $this->assertEquals([7, 2], $clubs->extract('player_count')->toArray());
        $this->assertEquals(
            [date('Y-m-d H:i', strtotime('1 day ago')), null],
            $clubs->extract('last_played')->map(function ($lastPlayed) {
                if (!$lastPlayed) {
                    return $lastPlayed;
                }

                return date('Y-m-d H:i', strtotime($lastPlayed));
            })->toArray()
        );
    }

    /**
     * @return void
     */
    public function testFindBanditId()
    {
        $club = $this->Clubs->get(1, ['finder' => 'banditId']);

        $this->assertEquals(6, $club->bandit_id);

        $club = $this->Clubs->get(2, ['finder' => 'banditId']);

        $this->assertEquals(8, $club->bandit_id);
    }
}
