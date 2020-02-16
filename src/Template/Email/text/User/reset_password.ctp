<?php
use Cake\Core\Configure;
?>
Your old password needs to be substituted, <?= $firstName ?>

One too many head-on tackles and you’ve forgotten your password. Not to worry.

Use our reset link below and write yourself a new one.

Make sure to pick a good one – or perhaps it’s time to try out a Password Manager like LastPass (http://lastpass.com) to remember for you?

Don't sit on the bench! This link will self-destruct in one hour...

------------------------------------------

Set a new password (<?= Configure::read('Bandit.appUrl') ?>/reset-password?token=<?= $token ?>)

------------------------------------------

Didn’t send a request?

If you suspect foul play, please reply to me (referee@banditmatch.com) and I’ll look into it for you.
