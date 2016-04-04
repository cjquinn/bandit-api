<?php

namespace App\Controller;

use Cake\Event\Event;

class TemplatesController extends AppController
{

	/**
	 * @return void
	 */
	public function beforeFilter(Event $event)
	{
		$this->Auth->allow();
	}

	/**
	 * @return void
	 */
	public function display($template)
	{
		$this->viewBuilder()->template($template);
	}
}
