<?php

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\I18n\Time;
use Cake\Mailer\MailerAwareTrait;

class SendChallengeFollowUpCommand extends Command
{
    use MailerAwareTrait;

    /**
     * @param \Cake\Console\Arguments $args
     * @param \Cake\Console\ConsoleIo $io
     * @return null|int
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->loadModel('Challenges');

        $challenges = $this->Challenges
            ->find()
            ->contain(['PlayerAs.Users', 'PlayerBs.Users'])
            ->where([
                $this->Challenges->aliasField('follow_up_sent') . ' IS' => null,
                $this->Challenges->aliasField('match_datetime') . ' <' => Time::now()
            ]);

        $challengeMailer = $this->getMailer('Challenge');
        $count = 0;

        foreach ($challenges as $challenge) {
            $challengeMailer->send(
                'followUp',
                [$challenge, $challenge->player_a, $challenge->player_b]
            );

            $challengeMailer->send(
                'followUp',
                [$challenge, $challenge->player_b, $challenge->player_a]
            );

            $challenge->set('follow_up_sent', Time::now());

            $this->Challenges->save($challenge);

            $count += 2;
        }

        $io->out(sprintf(
            '%d challenge follow ups sent',
            $count
        ));
    }
}
