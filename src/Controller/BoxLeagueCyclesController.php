<?php

namespace App\Controller;

class BoxLeagueCyclesController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if ($this->request->action === 'add') {
            if (!$this->BoxLeagueCycles->Clubs->Players->isFounder($this->Auth->user('player.id'), $this->request->params['club_id'])) {
                return false;
            }

            if ($this->BoxLeagueCycles->Clubs->hasUnfinishedBoxLeagueCycle($this->request->params['club_id'])) {
                return false;
            }
        }

        return parent::isAuthorized($user);
    }

    /**
     * @return void
     */
    public function add()
    {
        $boxLeagueCycle = $this->BoxLeagueCycles->newEntity();

        $boxLeagueCycle->set('club_id', $this->request->params['club_id']);

        $this->BoxLeagueCycles->save($boxLeagueCycle);

        $this->set([
            'boxLeagueCycle' => $boxLeagueCycle,
            '_serialize' => 'boxLeagueCycle'
        ]);
    }

    /**
     * @return void
     */
    public function edit()
    {
    }
}
