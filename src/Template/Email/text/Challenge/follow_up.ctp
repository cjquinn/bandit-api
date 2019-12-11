<?php
use Cake\Core\Configure;
?>
How was your match against <?= $opponentFullName ?>?

We hope that you found <?= $opponentFirstName ?> to be a well matched opponent.

------------------------------------------

Add match result (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/matches/add/challenges/<?= $challengeId ?>)

------------------------------------------

Match didn't happen?

If your opponent didn't turn up let us know by reporting them (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/challenges/<?= $challengeId ?>).

Getting the most out of Bandit Match

The more matches you play, the more accurate your rating becomes.

Once you have an accurate rating, finding a well matched opponent is as simple as selecting a player near your rating.
