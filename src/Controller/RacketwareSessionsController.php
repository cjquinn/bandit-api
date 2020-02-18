<?php

namespace App\Controller;

use Cake\Event\Event;

class RacketwareSessionsController extends AppController
{
    /**
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow();
    }

    /**
     * @return \Cake\Http\Response
     */
    public function webhook()
    {
        $errors = $this->RacketwareSessions->getValidator()->errors(
            $this->request->getData()
        );

        if (!empty($errors)) {
            $this->setResponse($this->getResponse()->withStatus(400));

            return $this->getResponse();
        }

        switch ($this->getRequest()->getData('action')) {
            case 'upload':
                return $this->add();
        }
    }

    /**
     * @return \Cake\Http\Response
     */
    private function add()
    {
        $racketwareSession = $this->RacketwareSessions->newEntity();

        $this->RacketwareSessions->patchEntityAdd(
            $racketwareSession,
            $this->getRequest()->getData()
        );

        $this->RacketwareSessions->save($racketwareSession);

        return $this->getResponse();
    }
}
