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
                        The Bandit Match network is pleased to announce it’s newest talent Darren Bills.
                    </p>

                    <h2 style="<?=$h2Styles?>">Start your career</h2>

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
                    <a href="#" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Go to your club
                        </span>
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
