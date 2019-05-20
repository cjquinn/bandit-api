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
    public function invitationUpdate(User $user)
    {
        $player = $user->players[0];

        $this
            ->to($user->email)
            ->subject('You\'ve been invited to join ' . $players->club->name . ' on Bandit Match')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set([
                'email' => $email,
                'player' => $player
            ])
            ->template('invitationUpdate')
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
