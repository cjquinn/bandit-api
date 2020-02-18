<?php

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\Controller\ControllerTestTrait;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class RacketwareSessionsControllerTest extends IntegrationTestCase
{
    use ControllerTestTrait;

    /**
     * @return void
     * @group testing
     */
    public function testWebhookBadData()
    {
        $this->post('/racketware-sessions/webhook.json');

        $this->assertResponseCode(400);
    }

    /**
     * @return void
     * @group testing
     */
    public function testWebhookPost()
    {
        $this->post('/racketware-sessions/webhook.json', [
            'player' => '123456789',
            'action' => 'upload',
            'data' => [
                'longestRallyByTime' => 29.804445266723633,
                // There will be other data but we aren't validating the data payload
            ]
        ]);

        $this->assertResponseCode(200);
    }
}
