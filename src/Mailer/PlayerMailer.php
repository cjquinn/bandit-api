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
            ->setEmailFormat('both')
            ->set([
                'clubName' => $player->club->name,
                'playerFullName' => $player->user->full_name
            ]);

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
            ->setEmailFormat('both')
            ->set([
                'challengeId' => $challenge->id,
                'challengeLocation' => $challenge->location,
                'clubId' => $player->club_id,
                'clubName' => $club->name,
                'matchDatetime' => $challenge->match_datetime->format('l jS F'),
                'playerFullName' => $challenge->player_a->user->full_name,
            ]);

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
            ->setEmailFormat('both')
            ->set([
                'clubName' => $player->club->name,
                'clubId' => $player->club_id,
                'playerEmail' => urlencode($player->user->email)
            ]);

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
            ->setSubject($match->player_a->user->full_name . ' has added a match result against you')
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->setEmailFormat('both')
            ->set([
                'clubId' => $player->club_id,
                'opponentFullName' => $match->player_a->user->full_name,
                'opponentFirstName' => $match->player_a->user->first_name,
                'matchId' => $match->id
            ]);

        $this->viewBuilder()->setTemplate('Player/match_added');
    }

    /**
     * @return void
     */
    public function weeklyDigest(
        Player $player,
        Club $club,
        Query $openChallenges,
        Query $newPlayers,
        Query $weeklyLeaderboard,
        Query $acceptedChallenges
    ) {
        $this
            ->setTo($player->user->email)
            ->setSubject($club->name . '\'s weekly digest')
            ->setFrom(Configure::read('Bandit.emails.referee'))
            ->setEmailFormat('both')
            ->set([
                'clubId' => $club->id,
                'clubName' => $club->name,
                'openChallenges' => $openChallenges
                    ->map(function ($challenge) {
                        return [
                            'id' => $challenge->id,
                            'time' => $challenge->match_datetime->format('l g:ia'),
                            'date' => $challenge->match_datetime->format('jS F'),
                            'player_a_name' => $challenge->player_a->user->display_name,
                            'player_a_rating' => $challenge->player_a->display_rating
                        ];
                    })
                    ->toArray(),
                'newPlayers' => $newPlayers
                    ->map(function ($player) {
                        return [
                            'id' => $player->id,
                            'name' => $player->user->display_name,
                            'rating' => $player->display_rating
                        ];
                    })
                    ->toArray(),
                'weeklyLeaderboard' => $weeklyLeaderboard
                    ->map(function ($player) {
                        return [
                            'id' => $player->id,
                            'name' => $player->user->display_name,
                            'change' => sprintf(
                                '%s%d %d win%s %d loss%s',
                                (int)$player->rating_change >= 0 ? '+' : '',
                                $player->rating_change,
                                $player->wins_change,
                                (int)$player->wins_change !== 1 ? 's' : '',
                                $player->losses_change,
                                (int)$player->losses_change !== 1 ? 'es' : ''
                            )
                        ];
                    })
                    ->toArray(),
                'acceptedChallenges' => $acceptedChallenges
                    ->map(function ($challenge) {
                        return [
                            'id' => $challenge->id,
                            'time' => $challenge->match_datetime->format('l g:ia'),
                            'date' => $challenge->match_datetime->format('jS F'),
                            'player_a_name' => $challenge->player_a->user->display_name,
                            'player_a_rating' => $challenge->player_a->display_rating,
                            'player_b_name' => $challenge->player_b->user->display_name,
                            'player_b_rating' => $challenge->player_b->display_rating
                        ];
                    })
                    ->toArray(),
            ]);

        $this->viewBuilder()->setTemplate('Player/weekly_digest');
    }
}
