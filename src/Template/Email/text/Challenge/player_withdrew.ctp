<?php
use Cake\Core\Configure;
?>
Cancellation! <?= $opponentFullName ?> withdrew from your challenge

Sorry to hear it, but <?= $opponentFirstName ?> has withdrawn from your challenge on <?= $matchDatetime ?>.

------------------------------------------

Check in on your challenge (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/challenges/<?= $challengeId ?>)
