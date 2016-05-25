<?php

namespace App\Controller;

class BoxLeagueCyclesController extends ApiController
{

    /**
     * @return bool
     */
    public function isAuthorized($user)
    {
        if (!$this->BoxLeagueCycles->Clubs->Players->isFounder($this->Auth->user('player.id'), $this->request->params['club_id'])) {
            return false;
        }

        if ($this->BoxLeagueCycles->Clubs->hasUnfinishedBoxLeagueCycle($this->request->params['club_id'])) {
            return false;
        }

        if ($this->request->action === 'edit' &&
            !$this->BoxLeagueCycles->isOwnedBy($this->request->params['id'], $this->request->params['club_id'])
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
    public function edit($id)
    {
        $boxLeagueCycle = $this->BoxLeagueCycles->get($id);

        $this->BoxLeagueCycles->patchEntity($boxLeagueCycle, $this->request->data, [
            'fieldList' => [
                'start',
                'end'
            ],
            'validate' => 'startCycle'
        ]);

        $this->set('boxLeagueCycle', $boxLeagueCycle);

        if ($this->BoxLeagueCycles->save($boxLeagueCycle)) {
            $this->set('_serialize', 'boxLeagueCycle');
        } else {
            $this->set([
                'errors' => $boxLeagueCycle->errors(),
                '_serialize' => true
            ]);

            $this->response->statusCode(400);
        }
    }
}
