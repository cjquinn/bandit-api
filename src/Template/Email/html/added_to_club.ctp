<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        A new player has entered the arena
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        <?= $player->club->name ?> is pleased to announce it’s newest talent&hellip;<?= $player->user->first_name ?> <?= $player->user->last_name ?>.
                    </p>

                    <h2 style="<?= Configure::read('Bandit.emailStyles.h2') ?>">Start your career</h2>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Play matches with your club-mates.
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Build your rating by facing off against new opponents.
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Compete on the Leaderboards to promote yourself to a higher rank.
                    </p>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 24px 35px;">
                    <a href="https://banditmatch.com/clubs" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Go to your club
                        </span>
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
