<?php

namespace App\Mailer;

use App\Model\Entity\Challenge;
use App\Model\Entity\Player;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;

class ChallengeMailer extends Mailer
{
    /**
     * @return void
     */
    public function playerAccepted(Challenge $challenge)
    {
        TableRegistry::get('Challenges')->loadInto($challenge, ['PlayerAs.Users', 'PlayerBs.Users']);

        $this
            ->to($challenge->player_a->user->email)
            ->subject('Player Accepted')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set(['challenge' => $challenge])
            ->template('playerAccepted')
            ->emailFormat('both');
    }

    /**
     * @return void
     */
    public function playerDeleted(Challenge $challenge)
    {
        TableRegistry::get('Challenges')->loadInto($challenge, ['PlayerAs.Users', 'PlayerBs.Users']);

        $this
            ->to($challenge->player_b->user->email)
            ->subject('Player Deleted')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set(['challenge' => $challenge])
            ->template('playerDeleted')
            ->emailFormat('both');
    }

    /**
     * @return void
     */
    public function playerWithdrew(Challenge $challenge, Player $playerB)
    {
        TableRegistry::get('Challenges')->loadInto($challenge, ['PlayerAs.Users']);
        TableRegistry::get('Players')->loadInto($playerB, ['Users']);

        $challenge->set('player_b', $playerB);

        $this
            ->to($challenge->player_a->user->email)
            ->subject('Player Withdrew')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set(['challenge' => $challenge])
            ->template('playerWithdrew')
            ->emailFormat('both');
    }
}
