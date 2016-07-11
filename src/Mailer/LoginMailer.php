<?php

namespace App\Mailer;

use App\Model\Entity\Login;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Mailer\Mailer;

class LoginMailer extends Mailer
{

    /**
     * @param \App\Model\Entity\Login $login The Login that needs to be activated
     * @return void
     */
    public function activateAccount(Login $login)
    {
        $this
            ->to($login->email)
            ->subject('Activate account')
            ->from(Configure::read('EmailAddresses.welcome'))
            ->set(['login' => $login])
            ->emailFormat('both');
    }

    /**
     * @param \App\Model\Entity\Login $login The Login that is needs a password reset
     * @return void
     */
    public function resetPassword(Login $login)
    {
        $this
            ->to($login->email)
            ->subject('Reset password')
            ->from(Configure::read('EmailAddresses.support'))
            ->set(['login' => $login])
            ->emailFormat('both');
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return defined('TESTING') ? [] : ['Model.afterSave' => 'onRegistration'];
    }

    /**
     * @return void
     */
    public function onRegistration(Event $event, Login $login)
    {
        if ($login->isNew()) {
            $this->send('activateAccount', [$login]);
        }
    }
}
