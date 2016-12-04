<header class="header g3">

    <div class="col">

        <h1 class="header__title h1">Dashboard</h1>

        <ol class="tabs header__tabs">
            <li class="tabs__tab is--active"><button class="tabs__tab__button">Activity</li>
            <li class="tabs__tab"><button class="tabs__tab__button">Notifications <span class="tabs__tab__count is--alert">5</span></li>
            <li class="tabs__tab"><button class="tabs__tab__button">Disputes <span class="tabs__tab__count">0</span></li>
        </ol>

    </div>

    <nav class="form__select header__select">
        <h1 class="h2">Dashboard</h1>
        
        <label>
            <select>
                <option disabled>Dashboard:</option>
                <option selected>Activity</option>
                <option>Notifications (5)</option>
                <option>Disputes (0)</option>
            </select>
            <?= $this->Svg->useit('rarr', 'rarr header__select__rarr') ?>
        </label>
    </nav>

</header>

<article class="display g2">

    <div class="col">

        <section class="block">

            <header class="block__header justify-space-between-baseline">
                <h1 class="h4">Matches</h1>
                <small class="h4__small">Monday 9th</small>
            </header>

            <ol class="stream matches">

                <?php

                    $stream = 1;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('matchBandit');

                    }

                    $stream = 3;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('match');

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

                    10 Players

                </li>


            </ul>

        </section>


        

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