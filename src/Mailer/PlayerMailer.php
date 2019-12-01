<?php

namespace App\Mailer;

use App\Model\Entity\Club;
use App\Model\Entity\Player;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class PlayerMailer extends Mailer
{
    /**
     * @return void
     */
    public function addedToClub(Player $player)
    {
        TableRegistry::get('Players')->loadInTo($player, ['Clubs', 'Users']);

        $this
            ->setTo($player->user->email)
            ->setSubject('You\'ve been added to ' . $player->club->name)
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->set([
                'clubName' => $player->club->name,
                'playerFullName' => $player->user->full_name
            ])
            ->setEmailFormat('both');

        $this->viewBuilder()->setTemplate('Player/added_to_club');
    }

    /**
     * @return void
     */
    public function invitedToClub(Player $player)
    {
        TableRegistry::get('Players')->loadInTo($player, ['Clubs', 'Users']);

        $this
            ->setTo($player->user->email)
            ->setSubject('You\'ve been invited to join ' . $player->club->name . ' on Bandit Match')
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->set([
                'clubName' => $player->club->name,
                'playerEmail' => urlencode($player->user->email)
            ])
            ->setEmailFormat('both');

        $this->viewBuilder()->setTemplate('Player/invited_to_club');
    }

    /**
     * @return void
     */
    public function weeklyDigest(
        Player $player,
        Club $club,
        Query $openChallenges,
        Query $acceptedChallenges,
        Query $newPlayers
    ) {
        $this
            ->setTo($player->user->email)
            ->setSubject($club->name . '\'s weekly digest on Bandit Match')
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->setEmailFormat('both');

        $this->viewBuilder()->setTemplate('Player/weekly_digest');
    }
}
