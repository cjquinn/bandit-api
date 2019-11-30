<?php
use Cake\Core\Configure;
?>
Game on! <?= $opponentFullName ?> has accepted your challenge

You're set to play on <?= $matchDatetime ?>. We look forward to seeing the match result!

------------------------------------------

Go to this challenge (<?= Configure::read('Bandit.appUrl') ?>/challenges/<?= $challengeId ?>)
