<?php
use Cake\Core\Configure;
?>
<?= $clubName ?>'s weekly digest
<?php if (!empty($openChallenges)) : ?>

Open challenges

The easiest way to get back on to the court.
<?php foreach ($openChallenges as $challenge) : ?>

<?= $challenge['time'] ?> <?= $challenge['date'] ?><?= "\n" ?>
<?= $challenge['player_a_name'] ?> <?= $challenge['player_a_rating'] ?><?= "\n" ?>
View (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/challenges/<?= $challenge['id'] ?>)
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($newPlayers)) : ?>

New players

There's no better way to welcome your new club mates then with a match.
<?php foreach ($newPlayers as $player) : ?>

<?= $player['name'] ?> <?= $player['rating'] ?><?= "\n" ?>
Challenge (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/players/<?= $player['id'] ?>)
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($weeklyLeaderboard)) : ?>

Weekly leaderboard

Who's been hitting and who's hitting back.
<?php foreach ($weeklyLeaderboard as $i => $player) : ?>

#<?= $i + 1 ?> <?= $player['name'] ?> <?= $player['change'] ?><?= "\n" ?>
View (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/player/<?= $player['id'] ?>)
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($acceptedChallenges)) : ?>

Accepted challenges

Keep an eye on the matches feed for the results to these showdowns.
<?php foreach ($acceptedChallenges as $challenge) : ?>

<?= $challenge['time'] ?> <?= $challenge['date'] ?><?= "\n" ?>
<?= $challenge['player_a_name'] ?> <?= $challenge['player_a_rating'] ?> vs <?= $challenge['player_b_name'] ?> <?= $challenge['player_b_rating'] ?><?= "\n" ?>
View (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/challenges/<?= $challenge['id'] ?>)
<?php endforeach; ?>
<?php endif; ?>

------------------------------------------

Go to your club (<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>/clubs/<?= $clubId ?>)

------------------------------------------

Getting the most out of Bandit Match

The more matches you play, the more accurate your rating becomes.

Once you have an accurate rating, finding a well matched opponent is as simple as selecting a player near your rating.
