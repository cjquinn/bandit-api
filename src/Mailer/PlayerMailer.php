<?php

namespace App\Mailer;

use App\Model\Entity\Player;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;

class PlayerMailer extends Mailer
{
    /**
     * @return void
     */
    public function addedToClub(Player $player)
    {
        TableRegistry::get('Players')->loadInto($player, ['Clubs', 'Users']);

        $this
            ->to($player->user->email)
            ->subject('You\'ve been added to ' . $player->club->name)
            ->from(Configure::read('Bandit.emails.referee'))
            ->set(['player' => $player])
            ->template('addedToClub')
            ->emailFormat('both');
    }

    /**
     * @return void
     */
    public function invitedToClub(Player $player)
    {
        TableRegistry::get('Players')->loadInto($player, ['Clubs', 'Users']);

        $this
            ->to($player->user->email)
            ->subject('You\'ve been invited to join ' . $player->club->name . ' on Bandit Match')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set(['player' => $player])
            ->template('invitedToClub')
            ->emailFormat('both');
    }
}
