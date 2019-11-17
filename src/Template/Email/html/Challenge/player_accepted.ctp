<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        Game on! <?= $challenge->player_b->user->full_name ?> has accepted your challenge
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        You're set to play on <?= $challenge->match_datetime->format('l jS F \a\t H:i') ?>. We look forward to seeing the match result!
                    </p>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 0px 35px;">
                    <a href="https://banditmatch.com/challenges/<?= $challenge->id ?>" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Go to this challenge
                        </span>
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
