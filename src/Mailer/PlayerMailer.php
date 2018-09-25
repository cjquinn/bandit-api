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
            ->subject('You\'ve been added to a new club')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set(['player' => $player])
            ->template('addedToClub')
            ->emailFormat('both');
    }
}
