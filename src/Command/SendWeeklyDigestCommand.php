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

        $clubs = $this->Clubs->find();

        // For each club
        foreach ($clubs as $club) {
            $clubsUsers = $club->users;
            unset($club->users);

            if (empty($clubsUsers)) {
                $io->out(sprintf(
                    '%s: %d weekly digest email%s sent',
                    $club->name,
                    0,
                    's'
                ));

                continue;
            }

            // Get this weeks leaderboard
            $weeklyLeaderboard = $this->Clubs->Players
                ->findByClubId($club->id)
                ->find('populated')
                ->find('weeklyLeaderboard');

            // Get open challenges
            $openChallenges = $this->Clubs->Challenges
                ->findByClubId($club->id)
                ->find(
                    'filtered',
                    ['filter' => 'open']
                );

            // Get accepted challenges
            $acceptedChallenges = $this->Clubs->Challenges
                ->findByClubId($club->id)
                ->find(
                    'filtered',
                    ['filter' => 'accepted']
                );

            // Get players that joined this week
            $startOfWeek = new Time('monday this week');

            $newPlayers = $this->Clubs->Players
                ->findByClubId($club->id)
                ->find('populated')
                ->where([
                    $this->Clubs->Players->aliasField('created') . ' >=' => $startOfWeek
                ]);

            if ($weeklyLeaderboard->isEmpty() &&
                $openChallenges->isEmpty() &&
                $acceptedChallenges->isEmpty() &&
                $newPlayers->isEmpty()
            ) {
                $io->out(sprintf(
                    '%s: %d weekly digest email%s sent',
                    $club->name,
                    0,
                    's'
                ));

                continue;
            }

            foreach ($clubsUsers as $user) {
                $playerMailer->send(
                    'weeklyDigest',
                    [$user, $club, $openChallenges, $acceptedChallenges, $newPlayers, $weeklyLeaderboard]
                );
            }

            $countUsers = count($users);

            $io->out(sprintf(
                '%s: %d weekly digest email%s sent',
                $club->name,
                $countUsers,
                $countUsers === 1 ? '' : 's'
            ));
        }
    }
}
