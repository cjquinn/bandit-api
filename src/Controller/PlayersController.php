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
                $this->Flash->success('Player invited');

                return $this->redirect([
                    'action' => 'add'
                ]);
            }

            $this->Flash->error('There was an error, please try again');
        }

        $this->set('player', $player);
    }

    /**
     * @return void
     */
    public function edit()
    {
        $player = $this->Players->get($this->Auth->user('player.id'), [
            'contain' => [
                'Logins'
            ]
        ]);

        if ($this->request->is('put')) {
            $this->Players->patchEntity($player, $this->request->data);

            if ($this->Players->save($player)) {
                $this->Flash->success('Profile updated');
            } else {
                $this->Flash->error('There was an error, please try again');
            }
        }

        $this->set('player', $player);
    }
}
