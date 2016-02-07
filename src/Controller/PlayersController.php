<?php

namespace App\Controller;

use Cake\Utility\Hash;

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

            if (!$player->errors()) {
                if (!empty(Hash::get($this->request->data, 'losing_profile_picture.tmp_name'))) {
                    $this->Players->setProfilePicture($player, $this->request->data['losing_profile_picture']['tmp_name'], 'losing');
                }
                
                if (!empty(Hash::get($this->request->data, 'winning_profile_picture.tmp_name'))) {
                    $this->Players->setProfilePicture($player, $this->request->data['winning_profile_picture']['tmp_name'], 'winning');
                }
            }

            if ($this->Players->save($player)) {
                $this->Flash->success('Profile updated');
            } else {
                $this->Flash->error('There was an error, please try again');
            }
        }

        $this->set('player', $player);
    }
}
