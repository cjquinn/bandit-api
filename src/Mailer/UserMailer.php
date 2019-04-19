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
            ->to($user->email)
            ->subject('Your old password needs to be substituted')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set(['user' => $user])
            ->template('resetPassword')
            ->emailFormat('both');
    }
}
