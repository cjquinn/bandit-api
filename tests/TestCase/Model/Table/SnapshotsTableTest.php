<?php

namespace App\Test\TestCase\Model\Table;

use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class SnapshotsTableTest extends TestCase
{

    public $fixtures = [
        'app.matches',
        'app.snapshots'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Snapshots = TableRegistry::get('Snapshots');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Snapshots);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testGetDailySnapshot()
    {
        // From today
        $date = new Time();
        $dailySnapshot = $this->Snapshots->getDailySnapshot(1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => 1215,
            'difference' => -23,
            'wins' => 2,
            'losses' => 1
        ];
        $this->assertEquals($expected, $dailySnapshot);

        // From 1 day ago
        $date = new Time('1 day ago');
        $dailySnapshot = $this->Snapshots->getDailySnapshot(1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => 1238,
            'difference' => 18,
            'wins' => 2,
            'losses' => 0
        ];
        $this->assertEquals($expected, $dailySnapshot);

        // From 2 days ago
        $date = new Time('2 days ago');
        $dailySnapshot = $this->Snapshots->getDailySnapshot(1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => 1238,
            'difference' => 18,
            'wins' => 2,
            'losses' => 0
        ];
        $this->assertEquals($expected, $dailySnapshot);

        // From 3 days ago
        $date = new Time('3 days ago');
        $dailySnapshot = $this->Snapshots->getDailySnapshot(1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => 1220,
            'difference' => 20,
            'wins' => 1,
            'losses' => 0
        ];
        $this->assertEquals($expected, $dailySnapshot);

        // From 4 days ago
        $date = new Time('4 days ago');
        $dailySnapshot = $this->Snapshots->getDailySnapshot(1, $date->i18nFormat('Y-M-d'));

        $expected = [
            'rating' => Configure::read('Bandit.initialRating'),
            'difference' => 0,
            'wins' => 0,
            'losses' => 0
        ];
        $this->assertEquals($expected, $dailySnapshot);
    }
}
