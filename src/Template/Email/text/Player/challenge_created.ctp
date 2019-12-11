<?php
use Cake\Core\Configure;
?>
<?= $playerFullName ?> has posted a new challenge in <?= $clubName ?>

The gauntlet has been thrown down, will you accept the challenge?

When

<?= $matchDatetime ?>

Where

<?= $challengeLocation ?>

------------------------------------------

Go to this challenge (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/challenges/<?= $challengeId ?>)
