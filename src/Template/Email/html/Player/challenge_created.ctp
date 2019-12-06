<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        <?= $playerFullName ?> has posted a new challenge in <?= $clubName ?>
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        The gauntlet has been thrown down, will you accept the challenge?
                    </p>

                    <h2 style="<?= Configure::read('Bandit.emailStyles.h2') ?>">
                        When
                    </h2>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        <?= $matchDatetime ?>
                    </p>

                    <h2 style="<?= Configure::read('Bandit.emailStyles.h2') ?>">
                        Where
                    </h2>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        <?= $challengeLocation ?>
                    </p>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 0px 35px;">
                    <a href="<?= Configure::read('Bandit.appUrl') ?>/challenges/<?= $challengeId ?>" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Go to this challenge
                        </span>
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
