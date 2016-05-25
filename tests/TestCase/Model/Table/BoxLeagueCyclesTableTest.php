<?php

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

use DateTime;

class BoxLeagueCyclesTableTest extends TestCase
{

    public $fixtures = [
        'app.box_league_cycles',
        'app.boxes',
        'app.clubs'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->BoxLeagueCycles = TableRegistry::get('BoxLeagueCycles');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->BoxLeagueCycles);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testNewBoxLeagueCycles()
    {
        $boxLeagueCycle = $this->BoxLeagueCycles->newEntity();

        $boxLeagueCycle->set('club_id', 1);

        $this->BoxLeagueCycles->save($boxLeagueCycle);

        $this->assertEquals(2, count($boxLeagueCycle->boxes));
    }

    /**
     * @return void
     */
    public function testValidationStartCycle()
    {
        $errors = $this->BoxLeagueCycles->validator('startCycle')->errors([
            'start' => (new DateTime('-1 week'))->format('Y-m-d'),
            'end' => (new DateTime('-3 weeks'))->format('Y-m-d')
        ]);

        $expected = [
            'start' => [
                'valid' => 'Start date cannot be in the past or after the end date.'
            ],
            'end' => [
                'valid' => 'The end date must be after the start date.'
            ]
        ];

        $this->assertEquals($expected, $errors);
    }
}
