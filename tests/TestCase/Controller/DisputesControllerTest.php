<?php

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class DisputesControllerTest extends IntegrationTestCase
{

    use ControllerTestTrait;

    /**
     * @return void
     */
    public function testAddUnauthorised()
    {
        $this->_setAjaxRequest();

        $this->post('/results/1/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddInvalidPlayerId()
    {
        $this->_setAuthSession(2);
        $this->_setAjaxRequest();

        $this->post('/results/1/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(403);
    }

    /**
     * @return void
     */
    public function testAddPost()
    {
        $this->_setAuthSession(1);
        $this->_setAjaxRequest();

        $this->post('/results/1/disputes.json', [
            'message' => ''
        ]);

        $this->assertResponseCode(200);
    }
}
