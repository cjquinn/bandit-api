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
        TableRegistry::get('Challenges')->loadInTo($challenge, ['PlayerAs.Users', 'PlayerBs.Users']);

        $this
            ->setTo($challenge->player_a->user->email)
            ->setSubject('Game on!' . $challenge->player_b->user->full_name . 'has accepted your challenge')
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->set(['challenge' => $challenge])
            ->setEmailFormat('both');

        $this->viewBuilder()->setTemplate('Challenge/player_accepted');
    }

    /**
     * @return void
     */
    public function playerDeleted(Challenge $challenge)
    {
        TableRegistry::get('Challenges')->loadInTo($challenge, ['PlayerAs.Users', 'PlayerBs.Users']);

        $this
            ->setTo($challenge->player_b->user->email)
            ->setSubject('Cancellation! ' . $challenge->player_a->user->full_name . ' has cancelled their challenge')
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->set(['challenge' => $challenge])
            ->setEmailFormat('both');

        $this->viewBuilder()->setTemplate('Challenge/player_deleted');
    }

    /**
     * @return void
     */
    public function playerWithdrew(Challenge $challenge, Player $playerB)
    {
        TableRegistry::get('Challenges')->loadInTo($challenge, ['PlayerAs.Users']);
        TableRegistry::get('Players')->loadInTo($playerB, ['Users']);

        $challenge->set('player_b', $playerB);

        $this
            ->setTo($challenge->player_a->user->email)
            ->setSubject('Cancellation! ' . $challenge->player_b->user->full_name . ' withdrew from your challenge')
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->set(['challenge' => $challenge])
            ->setEmailFormat('both');

        $this->viewBuilder()->setTemplate('Challenge/player_withdrew');
    }
}
