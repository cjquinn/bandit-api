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
    public function activateAccount(User $user)
    {
        $clubName = $user->clubId
            ? TableRegistry::get('Clubs')->get($user->clubId)
            : 'Bandit Match';

        $this
            ->to($user->email)
            ->subject('You\'ve been invited to join Bandit Match')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set([
                'clubName' => $clubName,
                'user' => $user
            ])
            ->template('activateAccount')
            ->emailFormat('both');
    }

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
