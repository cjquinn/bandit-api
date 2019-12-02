<?php

namespace App\Mailer;

use App\Model\Entity\Challenge;
use App\Model\Entity\Club;
use App\Model\Entity\Match;
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
    public function challengeCreated(Player $player, Challenge $challenge, Club $club)
    {
        $this
            ->setTo($player->user->email)
            ->setSubject($challenge->player_a->user->full_name . ' has posted a new challenge in ' . $club->name)
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->setEmailFormat('both');

        $this->viewBuilder()->setTemplate('Player/challenge_created');
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
    public function matchAdded(Player $player, Match $match)
    {
        TableRegistry::get('Matches')->loadInTo($match, ['PlayerAs.Users']);

        $this
            ->setTo($player->user->email)
            ->setSubject($match->player_a->user->full_name . ' has added a match against you')
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->setEmailFormat('both');

        $this->viewBuilder()->setTemplate('Player/match_added');
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
