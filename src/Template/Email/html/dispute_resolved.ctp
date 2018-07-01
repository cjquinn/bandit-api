<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">

        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        We've closed your dispute with Jeremy Banks
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Your clubmates will be pleased that you could compromise – and managed to keep the peace!
                    </p>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 24px 35px;">
                    <a href="#" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Go to closed dispute
                        </span>
                    </a>
                </td>
            </tr>
        </table>

    </td>
</tr>
