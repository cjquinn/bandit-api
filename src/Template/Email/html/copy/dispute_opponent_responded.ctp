<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">

        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        Jeremy Banks responded to your dispute
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        You have until 6:20PM Thursday 14th to close this dispute.
                    </p>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 0px 35px;">
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
