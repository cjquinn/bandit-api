<?php

namespace App\Controller\Api;

use Cake\Utility\Hash;

class PlayersController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if (!parent::isAuthorized($user)) {
            return false;
        }

        // Invalid player id
        if ($this->request->action === 'edit' &&
            $this->Auth->user('player.id') !== (int)$this->request->params['id']
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    public function add()
    {
        $this->request->data['clubs'] = [
            '_ids' => [
                $this->request->params['club_id']
            ]
        ];

        $player = $this->Players->newEntity($this->request->data, [
            'fieldList' => [
                'name',
                'login',
                'clubs'
            ]
        ]);

        $this->set('player', $player);

        if ($this->Players->save($player)) {
            $this->set('_serialize', 'player');
        } else {
            $this->set([
                'errors' => $player->errors(),
                '_serialize' => true
            ]);

            $this->response->statusCode(400);
        }
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

        $this->Players->patchEntity($player, $this->request->data);

        if (!$player->errors()) {
            if (!empty(Hash::get($this->request->data, 'losing_profile_picture.tmp_name'))) {
                $this->Players->setProfilePicture($player, $this->request->data['losing_profile_picture']['tmp_name'], 'losing');
            }
            
            if (!empty(Hash::get($this->request->data, 'winning_profile_picture.tmp_name'))) {
                $this->Players->setProfilePicture($player, $this->request->data['winning_profile_picture']['tmp_name'], 'winning');
            }
        }

        $this->set('player', $player);

        if ($this->Players->save($player)) {
            $this->set('_serialize', 'player');
        } else {
            $this->set([
                'errors' => $player->errors(),
                '_serialize' => true
            ]);

            $this->response->statusCode(400);
        }
    }
}
