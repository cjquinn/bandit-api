<?php
use Cake\Core\Configure;
?>

<tr>
    <td cellpadding="0" style="padding: 0;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" padding="0" width="100%" bgcolor="#1D253E" style="max-width: 400px; padding: 0 30px;">
            <tr>
                <td cellpadding="0" style="padding: 0;">
                    <h1 style="<?= Configure::read('Bandit.emailStyles.h1') ?>">
                        <?= $clubName ?>'s weekly digest
                    </h1>

                    <?php if (!empty($openChallenges)) : ?>
                        <h2 style="<?= Configure::read('Bandit.emailStyles.h2') ?>">Open challenges</h2>

                        <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                            The easiest way to get back on to the court.
                        </p>

                        <?php foreach ($openChallenges as $challenge) : ?>
                            <p>
                                <a href="<?= Configure::read('Bandit.appUrl') ?>/challenges/<?= $challenge['id'] ?>" style="display: block; height: 14px; padding: 18px 12px; background-color: #151828; text-decoration: none; border-top-left-radius: 4px; border-top-right-radius: 4px;">

                                    <span style="float: left; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 500; font-size: 14px; line-height: 1; color: #fff; text-decoration: none;">
                                        <?= $challenge['time'] ?>
                                    </span>

                                    <span style="float: right; margin-left: auto; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 500; font-size: 14px; line-height: 1; color: #fff; text-decoration: none;">
                                        <?= $challenge['date'] ?>
                                    </span>
                                </a>

                                <a href="<?= Configure::read('Bandit.appUrl') ?>/challenges/<?= $challenge['id'] ?>" style="display: block; height: 40px; padding: 12px 12px; background-color: #181d31; text-decoration: none; border-top-left-radius: 4px; border-top-right-radius: 4px;">
                                    <span style="float: left;">
                                        <span style="display: block; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 500; font-size: 15px; line-height: 21px; color: #dce4f7; text-decoration: none;">
                                            <?= $challenge['player_a_name'] ?>
                                        </span>

                                        <span style="display: block; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 300; font-size: 12px; line-height: 21px; color: #dce4f7; text-decoration: none; opacity: .8;">
                                            <?= $challenge['player_a_rating'] ?>
                                        </span>
                                    </span>

                                    <span style="float: right; margin-top: 5px; background: #ff7048; border-radius: 6px; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 700; font-size: 14px; padding: 6px 9px; color: #000207; text-shadow: 0 2px 0 #f29e44;">
                                        View
                                    </span>
                                </a>
                            </p>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($newPlayers)) : ?>
                        <h2 style="<?= Configure::read('Bandit.emailStyles.h2') ?>">New players</h2>

                        <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                            There's no better way to welcome your new club mates then with a match.
                        </p>

                        <?php foreach ($newPlayers as $player) : ?>
                            <p>
                                <a href="<?= Configure::read('Bandit.appUrl') ?>/players/<?= $player['id'] ?>" style="display: block; height: 40px; padding: 12px 12px; background-color: #181d31; text-decoration: none; border-top-left-radius: 4px; border-top-right-radius: 4px;">
                                    <span style="float: left;">
                                        <span style="display: block; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 500; font-size: 15px; line-height: 21px; color: #dce4f7; text-decoration: none;">
                                            <?= $player['name'] ?>
                                        </span>

                                        <span style="display: block; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 300; font-size: 12px; line-height: 21px; color: #dce4f7; text-decoration: none; opacity: .8;">
                                            <?= $player['rating'] ?>
                                        </span>
                                    </span>

                                    <span style="float: right; margin-top: 5px; background: #ff7048; border-radius: 6px; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 700; font-size: 14px; padding: 6px 9px; color: #000207; text-shadow: 0 2px 0 #f29e44;">
                                        Challenge
                                    </span>
                                </a>
                            </p>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($weeklyLeaderboard)) : ?>
                        <h2 style="<?= Configure::read('Bandit.emailStyles.h2') ?>">Weekly leaderboard</h2>

                        <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                            Who's been hitting and who's hitting back.
                        </p>

                        <?php foreach ($weeklyLeaderboard as $i => $player) : ?>
                            <p>
                                <a href="<?= Configure::read('Bandit.appUrl') ?>/players/<?= $player['id'] ?>" style="display: block; height: 40px; padding: 12px 12px; background-color: #181d31; text-decoration: none; border-top-left-radius: 4px; border-top-right-radius: 4px;">
                                    <span style="float: left;">
                                        <span style="display: block; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 500; font-size: 15px; line-height: 21px; color: #dce4f7; text-decoration: none;">
                                            <?= $player['name'] ?>
                                        </span>

                                        <span style="display: block; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 300; font-size: 12px; line-height: 21px; color: #dce4f7; text-decoration: none; opacity: .8;">
                                            <?= $player['change'] ?>
                                        </span>
                                    </span>

                                    <span style="float: right; margin-top: 5px; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 700; font-size: 14px; padding: 6px 0; color: #ffffff;">
                                        #<?= $i + 1 ?>
                                    </span>
                                </a>
                            </p>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($acceptedChallenges)) : ?>
                        <h2 style="<?= Configure::read('Bandit.emailStyles.h2') ?>">Accepted challenges</h2>

                        <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                            Keep an eye on the matches feed for the results to these showdowns.
                        </p>

                        <?php foreach ($acceptedChallenges as $challenge) : ?>
                            <p>
                                <a href="<?= Configure::read('Bandit.appUrl') ?>/challenges/<?= $challenge['id'] ?>" style="display: block; height: 14px; padding: 18px 12px; background-color: #151828; text-decoration: none; border-top-left-radius: 4px; border-top-right-radius: 4px;">

                                    <span style="float: left; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 500; font-size: 14px; line-height: 1; color: #fff; text-decoration: none;">
                                        <?= $challenge['time'] ?>
                                    </span>

                                    <span style="float: right; margin-left: auto; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 500; font-size: 14px; line-height: 1; color: #fff; text-decoration: none;">
                                        <?= $challenge['date'] ?>
                                    </span>
                                </a>

                                <a href="<?= Configure::read('Bandit.appUrl') ?>/challenges/<?= $challenge['id'] ?>" style="display: block; height: 40px; padding: 12px 12px; background-color: #181d31; text-decoration: none; border-top-left-radius: 4px; border-top-right-radius: 4px;">
                                    <span style="float: left;">
                                        <span style="display: block; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 500; font-size: 15px; line-height: 21px; color: #dce4f7; text-decoration: none;">
                                            <?= $challenge['player_a_name'] ?>
                                        </span>

                                        <span style="display: block; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 300; font-size: 12px; line-height: 21px; color: #dce4f7; text-decoration: none; opacity: .8;">
                                            <?= $challenge['player_a_rating'] ?>
                                        </span>
                                    </span>

                                    <span style="float: right;">
                                        <span style="display: block; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 500; font-size: 15px; line-height: 21px; color: #dce4f7; text-decoration: none; text-align: right;">
                                            <?= $challenge['player_b_name'] ?>
                                        </span>

                                        <span style="display: block; font-family: Helvetica Neue, Helvetica, sans-serif; font-weight: 300; font-size: 12px; line-height: 21px; color: #dce4f7; text-decoration: none; opacity: .8; text-align: right;">
                                            <?= $challenge['player_b_rating'] ?>
                                        </span>
                                    </span>
                                </a>
                            </p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 48px 0px 35px;">
                    <a href="<?= Configure::read('Bandit.appUrl') ?>/clubs/<?= $clubId ?>" style="<?= Configure::read('Bandit.emailStyles.button') ?>">
                        <span style="<?= Configure::read('Bandit.emailStyles.buttonText') ?>">
                            Go to your club
                        </span>
                    </a>
                </td>
            </tr>

            <tr>
                <td cellpadding="0" style="padding: 0px 0px 25px;">
                    <h2 style="<?= Configure::read('Bandit.emailStyles.h2') ?>">
                        Getting the most out of Bandit Match
                    </h2>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        The more matches you play, the more accurate your rating becomes.
                    </p>

                    <p style="<?= Configure::read('Bandit.emailStyles.p') ?>">
                        Once you have an accurate rating, finding a well matched opponent is as simple as selecting a player near your rating.
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>
