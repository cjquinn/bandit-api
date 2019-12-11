<?php
use Cake\Core\Configure;
?>
Cancellation! <?= $opponentFullName ?> has cancelled their challenge

Sorry to hear it, but <?= $opponentFirstName ?>'s challenge for <?= $matchDatetime ?> was cancelled.

Make sure to check for any new Open Challenges, or find an opponent by adding a challenge of your own.

------------------------------------------

Go to Challenges (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/challenges)
