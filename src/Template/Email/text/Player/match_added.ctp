<?php
use Cake\Core\Configure;
?>

<?= $opponentFullName ?> just added a match result against you

We hope that you found <?= $opponentFirstName ?> to be a well matched opponent.

------------------------------------------

Go to this match (<?= Configure::read('Bandit.appUrl') ?>/matches/<?= $matchId ?>)

------------------------------------------

Match didn't happen or something wrong with the result added?

Get in touch with the ref (referee@banditmatch.com) to resolve any foul play.

Getting the most out of Bandit Match

The more matches you play, the more accurate your rating becomes.

Once you have an accurate rating, finding a well matched opponent is as simple as selecting a player near your rating.
