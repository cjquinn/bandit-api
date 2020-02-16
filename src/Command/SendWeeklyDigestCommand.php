<?php

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\I18n\Time;
use Cake\Mailer\MailerAwareTrait;

class SendWeeklyDigestCommand extends Command
{
    use MailerAwareTrait;

    /**
     * @param \Cake\Console\Arguments $args
     * @param \Cake\Console\ConsoleIo $io
     * @return null|int
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->loadModel('Clubs');

        $playerMailer = $this->getMailer('Player');

        $clubs = $this->Clubs
            ->find()
            ->contain('Players', function ($q) {
                $q
                    ->contain('Users')
                    ->innerJoinWith('Users', function ($q) {
                        $q->find('byEmailPreference', ['preference' => 'weekly_digest']);

                        return $q;
                    });

                return $q;
            });

        // For each club
        foreach ($clubs as $club) {
            if (empty($club->players)) {
                $io->out(sprintf(
                    '%s: %d weekly digest emails sent',
                    $club->name,
                    0
                ));

                continue;
            }

            // Get open challenges
            $openChallenges = $this->Clubs->Challenges
                ->findByClubId($club->id)
                ->find(
                    'filtered',
                    ['filter' => 'open']
                )
                ->find(
                    'byPlayerId',
                    ['player_id' => 'all']
                )
                ->find('populated');

            // Get players that joined this week
            $startOfWeek = new Time('monday this week');

            $newPlayers = $this->Clubs->Players
                ->findByClubId($club->id)
                ->find('populated')
                ->where([
                    $this->Clubs->Players->aliasField('created') . ' >=' => $startOfWeek
                ]);

            // Get this weeks leaderboard
            $weeklyLeaderboard = $this->Clubs->Players
                ->findByClubId($club->id)
                ->find('populated')
                ->find('weeklyLeaderboard');

            // Get accepted challenges
            $acceptedChallenges = $this->Clubs->Challenges
                ->findByClubId($club->id)
                ->find(
                    'filtered',
                    ['filter' => 'accepted']
                )
                ->find(
                    'byPlayerId',
                    ['player_id' => 'all']
                )
                ->find('populated');

            if ($openChallenges->isEmpty() &&
                $newPlayers->isEmpty() &&
                $weeklyLeaderboard->isEmpty() &&
                $acceptedChallenges->isEmpty()
            ) {
                $io->out(sprintf(
                    '%s: %d weekly digest emails sent',
                    $club->name,
                    0
                ));

                continue;
            }

            foreach ($club->players as $player) {
                $playerMailer->send(
                    'weeklyDigest',
                    [$player, $club, $openChallenges, $newPlayers, $weeklyLeaderboard, $acceptedChallenges]
                );
            }

            $io->out(sprintf(
                '%s: %d weekly digest emails sent',
                $club->name,
                count($club->players)
            ));
        }
    }
}
