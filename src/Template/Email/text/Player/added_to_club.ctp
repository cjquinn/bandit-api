<?php
use Cake\Core\Configure;
?>
A new player has entered the arena

<?= $clubName ?> is pleased to announce it’s newest talent...<?= $playerFullName ?>.

------------------------------------------

Go to your club (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/clubs)

------------------------------------------

Start your career

Play a range of club mates to find your rating.

Challenge players nearest your rating to have the best matches.

Compete on the Leaderboards to promote yourself to a higher rank.
