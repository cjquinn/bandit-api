<?php

namespace App\Mailer;

use App\Model\Entity\User;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    /**
     * @return void
     */
    public function activateAccount(User $user)
    {
        $this
            ->to($user->email)
            ->subject('Activate Account')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set([
                'url' => sprintf(
                    'https://banditplay.com/activate-account?token=%s',
                    $user->token
                )
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
            ->subject('Reset Password')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set([
                'name' => $user->name,
                'url' => sprintf(
                    'https://banditplay.com/reset-password?token=%s',
                    $user->token
                )
            ])
            ->template('resetPassword')
            ->emailFormat('both');
    }
}
