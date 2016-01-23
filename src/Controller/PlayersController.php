<?php

namespace App\Controller;

class PlayersController extends AppController
{

    /**
     * @return void
     */
    public function add()
    {
    	$player = $this->Players->newEntity();

    	if ($this->request->is('post')) {
    		$this->Players->patchEntity($player, $this->request->data);

    		if ($this->Players->save($player)) {
    			return $this->redirect([
    				'action' => 'add'
    			]);
    		}

    		$this->Flash->error('There was an error, please try again');
    	}

    	$this->set('player', $player);
    }
}
