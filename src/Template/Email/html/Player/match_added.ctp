<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        <?= $opponentFullName ?> has added a match result against you
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        We hope that you found <?= $opponentFirstName ?> to be a well matched opponent.
                    </p>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 0px 35px;">
                    <a href="<?= Configure::read('Bandit.appUrl') ?>/matches/<?= $matchId ?>" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Go to this match
                        </span>
                    </a>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 0px 0px 25px;">
                    <h2 style="<?= Configure::read('Bandit.emailStyles.h2') ?>">
                        Match didn't happen or something wrong with the result added?
                    </h2>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Get in touch with the <a href="mailto:referee@banditmatch.com" style="<?= Configure::read('Bandit.emailStyles.a') ?>">ref</a> to resolve any foul play.
                    </p>

                    <h2 style="<?= Configure::read('Bandit.emailStyles.h2') ?>">
                        Getting the most out of Bandit Match
                    </h2>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        The more matches you play, the more accurate your rating becomes.
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Once you have an accurate rating, finding a well matched opponent is as simple as selecting a player near your rating.
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>
