<?php

namespace App\Controller;

use Cake\Event\Event;

class ClubsController extends ApiController
{

    /**
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['add']);
    }

    /**
     * @return void
     */
    public function add()
    {
        $fieldList = ['name'];

        if ($this->Auth->user()) {
            $this->request->data['founding_player_id'] = $this->Auth->user('player.id');

            $fieldList[] = 'founding_player_id';
        } else {
            $fieldList[] = 'founding_player';
        }

        $club = $this->Clubs->newEntity($this->request->data, [
            'fieldList' => $fieldList
        ]);

        $this->set('club', $club);

        if ($this->Clubs->save($club)) {
            $this->set('_serialize', 'club');
        } else {
            $this->set([
                'errors' => $club->errors(),
                '_serialize' => true
            ]);

            $this->response->statusCode(400);
        }
    }
}
