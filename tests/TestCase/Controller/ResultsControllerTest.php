<?php

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

class ResultsControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testAddUnauthorised()
    {
        $this->get('/add-results');

        $this->assertRedirect([
            'controller' => 'Logins',
            'action' => 'login'
        ]);
    }

    /**
     * @return void
     */
    public function testAddGet()
    {
        $this->_setAuthSession('1');

        $this->get('/add-results');

        $this->assertResponseOk();
    }
}
