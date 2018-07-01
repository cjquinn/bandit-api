<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        Play better matches at Bandit Match
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Your friend Chris Jenkins has invited you to join the club at Britannia Squash.
                    </p>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 24px 35px;">
                    <a href="#" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Join the club
                        </span>
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>

<!-- <p>To activate your account, please click <?= $this->Html->link('here', $url) ?>.</p> -->
