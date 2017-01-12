<header class="header">

    <h1 class="header__title h1">Dashboard</h1>

</header>

<article class="display g2">

    <div class="col">

        <section class="block">

            <header class="block__header">
                <h1 class="h4">Disputes</h1>
                <small class="h4__small">You have 1 open dispute</small>
            </header>

            <ol class="stream disputes">

                <?php

                    for ($k = 0; $k < 1; $k++) {

                        echo $this->element('result');

                    }

                ?>

            </ol>

        </section>

        <section class="block">

            <a href="/matches" class="block__header block__header--link">
                <h1 class="h4">Matches</h1>
                <small class="h4__small">See newest games</small>
            </a>

            <ol class="stream matches results">

                <?php

                    $stream = 5;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('result');

                    }

                ?>

            </ol>

        </section>

            <?php /*

            <ul class="block__footer__meta">

                <li class="block__footer__meta__item">

                    <?= $this->Svg->useit('icon-matches', 'block__footer__icon'); ?>

                    5 Matches

                </li>

                <li class="block__footer__meta__item">

                    <?= $this->Svg->useit('icon-players', 'block__footer__icon'); ?>

                    10 Players

                </li>

            </ul>

            */ ?>





    </div>

    <div class="col">

       <section class="block">

            <header class="block__header justify-space-between-baseline">
                <h1 class="h4"><a href="/templates/boxleague">Box 1</a></h1>
                <small class="h4__small">3 weeks left</small>
            </header>

            <ol class="stream matches">

                <?php

                    $stream = 1;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('matchBandit');

                    }

                    $stream = 2;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('match');

                    }

                    $stream = 1;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('matchDraw');

                    }

                    $stream = 1;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('matchLoss');

                    }
                ?>

            </ol>

            <ul class="block__footer__meta">

                <li class="block__footer__meta__item">

                    <?= $this->Svg->useit('icon-matches', 'block__footer__icon'); ?>

                    5 Matches

                </li>

                <li class="block__footer__meta__item">

                    <?= $this->Svg->useit('icon-players', 'block__footer__icon'); ?>

                    8 Players

                </li>


            </ul>

        </section>

    </div>

</article>

<article class="display g2">

    <div class="col">

        <?=$this->element('leaderboardSummary')?>

    </div>

</article>
