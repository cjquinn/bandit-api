<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">

        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        Your recent match was disputed by your opponent – Jeremy Banks.
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Not to worry, sometimes we have a lapse in memory.
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        At 4:51PM on Tuesday 13th, you submitted a match result 6 - 4 against Jeremy Banks.
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        They have opened a dispute because they believe that the match score is incorrect.
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Make haste! You have until 6:20PM Thursday 14th to close this dispute.
                    </p>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 24px 35px;">
                    <a href="#" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Respond to this dispute
                        </span>
                    </a>
                </td>
            </tr>
        </table>

    </td>
</tr>
