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
            ->subject('Game on!' . $challenge->player_b->user->full_name . 'has accepted your challenge')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set(['challenge' => $challenge])
            ->template('Challenge/player_accepted')
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
            ->subject('Cancellation! ' . $challenge->player_a->user->full_name . ' has cancelled their challenge')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set(['challenge' => $challenge])
            ->template('Challenge/player_deleted')
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
            ->subject('Cancellation! ' . $challenge->player_b->user->full_name . ' withdrew from your challenge')
            ->from(Configure::read('Bandit.emails.referee'))
            ->set(['challenge' => $challenge])
            ->template('Challenge/player_withdrew')
            ->emailFormat('both');
    }
}
