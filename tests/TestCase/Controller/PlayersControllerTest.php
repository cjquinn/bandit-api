<?php

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

class PlayersControllerTest extends IntegrationTestCase
{

	use ControllerTestTrait;

	/**
	 * @return void
	 */
	public function testAddUnauthorised()
	{
		$this->get('/invite-player');

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

		$this->get('/invite-player');

		$this->assertResponseOk();
	}

	/**
	 * @return void
	 */
	public function testAddBadData()
	{
		$this->_setAuthSession('1');

		$this->post('/invite-player', [
			'name' => '',
			'login' => [
				'email' => ''
			]
		]);

		$this->assertNoRedirect();
	}

	/**
	 * @return void
	 */
	public function testAddPost()
	{
		$this->_setAuthSession('1');

		$this->post('/invite-player', [
			'name' => 'Russell Bishop',
			'login' => [
				'email' => 'russell@bandit.localhost'
			]
		]);

		$this->assertRedirect([
			'controller' => 'Players',
			'action' => 'add'
		]);
	}
}