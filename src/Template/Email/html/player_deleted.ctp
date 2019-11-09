<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        Cancellation! <?= $challenge->player_a->user->first_name ?> <?= $challenge->player_a->user->last_name ?> has cancelled their challenge
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Sorry to hear it, but <?= $challenge->player_b->user->first_name ?>'s challenge for <?= $challenge->match_datetime->format('l jS F at H:i') ?> was cancelled.
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Make sure to check for any new Open Challenges, or find an opponent by adding a challenge of your own.
                    </p>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 0px 35px;">
                    <a href="https://banditmatch.com/challenges" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Go to Challenges
                        </span>
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
