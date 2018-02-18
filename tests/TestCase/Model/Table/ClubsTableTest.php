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
    }

    /**
     * @return void
     */
    public function testDailySnapshot()
    {
        // From today
        $date = new Time();
        $dailySnapshot = $this->Clubs->dailySnapshot(1, 1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => 1215,
            'difference' => -23,
            'wins' => 2,
            'losses' => 1
        ];
        $this->assertEquals($expected, $dailySnapshot);

        // From 1 day ago
        $date = new Time('1 day ago');
        $dailySnapshot = $this->Clubs->dailySnapshot(1, 1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => 1238,
            'difference' => 18,
            'wins' => 2,
            'losses' => 0
        ];
        $this->assertEquals($expected, $dailySnapshot);

        // From 2 days ago
        $date = new Time('2 days ago');
        $dailySnapshot = $this->Clubs->dailySnapshot(1, 1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => 1238,
            'difference' => 18,
            'wins' => 2,
            'losses' => 0
        ];
        $this->assertEquals($expected, $dailySnapshot);

        // From 3 days ago
        $date = new Time('3 days ago');
        $dailySnapshot = $this->Clubs->dailySnapshot(1, 1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => 1220,
            'difference' => 20,
            'wins' => 1,
            'losses' => 0
        ];
        $this->assertEquals($expected, $dailySnapshot);

        // From 4 days ago
        $date = new Time('4 days ago');
        $dailySnapshot = $this->Clubs->dailySnapshot(1, 1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => Configure::read('Bandit.initialRating'),
            'difference' => 0,
            'wins' => 0,
            'losses' => 0
        ];
        $this->assertEquals($expected, $dailySnapshot);

        // 4 days ago deleted, from 3 days ago
        $this->Clubs->Matches->updateAll(['deleted' => new Time()], ['id' => 1]);

        $date = new Time('3 days ago');
        $dailySnapshot = $this->Clubs->dailySnapshot(1, 1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => Configure::read('Bandit.initialRating'),
            'difference' => 0,
            'wins' => 0,
            'losses' => 0
        ];
        $this->assertEquals($expected, $dailySnapshot);
    }
}
