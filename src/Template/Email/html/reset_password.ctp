<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">

        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        Your old password needs to be substituted, <?= $user->first_name ?>
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        One too many head-on tackles and you’ve forgotten your password. Not to worry.
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Use our reset link below and write yourself a new one.
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Make sure to pick a good one – or perhaps it’s time to try out a <a href="http://lastpass.com/" style="color: #3CB1FF;">Password Manager like LastPass</a> to remember for you?
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Don't sit on the bench! This link will self-destruct in one hour&hellip;
                    </p>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 0px 35px;">
                    <a href="https://banditmatch.com/reset-password?token=<?= $user->token ?>" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Set a new password
                        </span>
                    </a>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        Didn’t send a request?
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        If you suspect foul play, <a href="mailto:referee@banditmatch.com?subject=Suspected Foul Play" style="color: #3CB1FF;">please reply to me</a> and I’ll look into it for you.
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>
