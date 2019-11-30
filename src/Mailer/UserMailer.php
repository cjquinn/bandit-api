<?php

namespace App\Mailer;

use App\Model\Entity\User;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    /**
     * @return void
     */
    public function resetPassword(User $user)
    {
        $this
            ->setTo($user->email)
            ->setSubject('Your old password needs to be substituted')
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->set([
                'firstName' => $user->first_name,
                'token' => $user->token
            ])
            ->setEmailFormat('both');

        $this->viewBuilder()->setTemplate('User/reset_password');
    }
}
