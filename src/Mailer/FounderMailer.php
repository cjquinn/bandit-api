<?php

namespace App\Mailer;

use App\Model\Entity\BoxMatch;

use Cake\Event\Event;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;

class FounderMailer extends Mailer
{

    /**
     * @param \App\Model\Entity\BoxMatch $boxMatch
     * @return void
     */
    public function boxMatchDispute(BoxMatch $boxMatch)
    {
        TableRegistry::get('BoxMatches')->loadInto($boxMatch, [
            'Boxes.BoxLeagueCycles.Clubs.FoundingPlayers.Logins',
            'LosingPlayer.Logins',
            'WinningPlayer.Logins'
        ]);

        $this
            ->to($boxMatch->box->box_league_cycle->club->founding_player->login->email)
            ->cc($boxMatch->winning_player->login->email)
            ->subject('Box Match Dispute')
            ->from($boxMatch->losing_player->login->email)
            ->set(['boxMatch' => $boxMatch])
            ->emailFormat('both');
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return defined('TESTING') ? [] : ['Model.BoxMatch.disputed' => 'onBoxMatchDisputed'];
    }

    /**
     * @return void
     */
    public function onBoxMatchDisputed(Event $event, BoxMatch $boxMatch)
    {
        $this->send('boxMatchDispute', $boxMatch);
    }
}
