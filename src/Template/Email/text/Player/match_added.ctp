<?php
use Cake\Core\Configure;
?>

<?= $opponentFullName ?> just added a match result against you

We hope that you found <?= $opponentFirstName ?> to be a well matched opponent.

------------------------------------------

Go to this match (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/matches/<?= $matchId ?>)

------------------------------------------

Match didn't happen or something wrong with the result added?

Get in touch with the ref (referee@banditmatch.com) to resolve any foul play.
