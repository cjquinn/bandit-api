<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        Your dispute with Jeremy ended as unresolved.
                    </h1>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Each dispute that closes as unresolved will have the following consequences:
                    </p>

                    <ul>
                        <li>Match is deleted and rating changes are restored</li>
                        <li>Both players lose 10 reputation points</li>
                    </ul>
                </td>
            </tr>
        </table>
    </td>
</tr>
