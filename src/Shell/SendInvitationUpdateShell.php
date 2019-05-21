<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Mailer\MailerAwareTrait;

class SendInvitationUpdateShell extends Shell
{
    use MailerAwareTrait;

    public $modelClass = 'Users';

    /**
     * @return void
     */
    public function test()
    {
        $users = $this->Users
            ->find()
            ->where(['password IS' => null])
            ->contain('Players.Clubs.Matches')
            ->innerJoinWith('Players.Clubs')
            ->group('Users.id');

        foreach ($users as $user) {
            $this->out('Email: ' . $user->email);
            $this->out('Club: ' . $user->players[0]->club->name);
        }

        $this->out('=======================');
        $this->out('Count: ' . $users->count());
        $this->out('=======================');
    }

    /**
     * @return void
     */
    public function send()
    {
        $users = $this->Users
            ->find()
            ->where(['password IS' => null])
            ->contain('Players.Clubs.Matches')
            ->innerJoinWith('Players.Clubs')
            ->group('Users.id');

        foreach ($users as $user) {
            $this->getMailer('User')->send(
                'invitationUpdate',
                [$user]
            );
        }
    }
}
