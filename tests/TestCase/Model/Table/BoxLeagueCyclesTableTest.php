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
            'start' => 'Not a date',
            'end' => ''
        ]);

        $expected = [
            'start' => [
                'date' => 'The provided value is invalid'
            ],
            'end' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'boxes' => [
                '_required' => 'This field is required'
            ]
        ];

        $this->assertEquals($expected, $errors);

        $errors = $this->BoxLeagueCycles->validator('startCycle')->errors([
            'start' => (new DateTime('-1 week'))->format('Y-m-d'),
            'end' => (new DateTime('-3 weeks'))->format('Y-m-d'),
            'boxes' => []
        ]);

        $expected = [
            'start' => [
                'valid' => 'Start date cannot be in the past or after the end date.'
            ],
            'end' => [
                'valid' => 'The end date must be after the start date.'
            ],
            'boxes' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];

        $this->assertEquals($expected, $errors);

        $errors = $this->BoxLeagueCycles->validator('startCycle')->errors([
            'start' => (new DateTime())->format('Y-m-d'),
            'end' => (new DateTime('+2 weeks'))->format('Y-m-d'),
            'boxes' => [
                []
            ]
        ]);

        $expected = [
            'boxes' => [
                'hasAtLeast' => 'The provided value is invalid'
            ]
        ];

        $this->assertEquals($expected, $errors);

        $errors = $this->BoxLeagueCycles->validator('startCycle')->errors([
            'start' => (new DateTime())->format('Y-m-d'),
            'end' => (new DateTime('+2 weeks'))->format('Y-m-d'),
            'boxes' => [
                [
                    'players' => [1, 2, 3, 4]
                ],
                [
                    'players' => [1, 2, 3]
                ]
            ]
        ]);

        $expected = [
            'boxes' => [
                '1' => [
                    'players' => [
                        'hasAtLeast' => 'The provided value is invalid'
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $errors);
    }
}
