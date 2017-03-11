<?php

namespace App\Controller\Api;

use Cake\Utility\Hash;

class PlayersController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        // Invalid player id
        if ($this->request->action === 'edit' &&
            $this->Auth->user('player.id') !== (int)$this->request->params['id']
        ) {
            return false;
        }

        return parent::isAuthorized($user);
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
            'associated' => [
                'Clubs' => [
                    'onlyIds' => true
                ],
                'Logins' => [
                    'validate' => 'email'
                ]
            ],
            'fieldList' => [
                'name',
                'login',
                'clubs'
            ]
        ]);

        if (!$this->Players->save($player)) {
            $this->response->statusCode(400);
        }

        $this->set([
            'player' => $player,
            'errors' => $player->errors()
        ]);
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

        // if (!$player->errors()) {
        //     if (!empty(Hash::get($this->request->data, 'losing_profile_picture.tmp_name'))) {
        //         $this->Players->setProfilePicture(
        //             $player,
        //             $this->request->data['losing_profile_picture']['tmp_name'],
        //             'losing'
        //         );
        //     }

        //     if (!empty(Hash::get($this->request->data, 'winning_profile_picture.tmp_name'))) {
        //         $this->Players->setProfilePicture(
        //             $player,
        //             $this->request->data['winning_profile_picture']['tmp_name'],
        //             'winning'
        //         );
        //     }
        // }

        if (!$this->Players->save($player)) {
            $this->response->statusCode(400);
        }

        $this->set([
            'player' => $player,
            'errors' => $player->errors()
        ]);
    }
}
