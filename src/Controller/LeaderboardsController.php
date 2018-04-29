<?php

namespace App\Controller;

class LeaderboardsController extends AppController
{
    public $modelClass = 'Players';

    /**
     * @return void
     */
    public function allTime()
    {
        $players = $this->Players
            ->findByClubId($this->request->getParam('club_id'))
            ->find('populated')
            ->find('allTimeLeaderboard');

        $this->set('players', $players);
    }

    /**
     * @return void
     */
    public function weekly()
    {
        $players = $this->Players
            ->findByClubId($this->request->getParam('club_id'))
            ->find('populated')
            ->find('weeklyLeaderboard');

        $this->set('players', $players);
    }
}
