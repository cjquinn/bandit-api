<?php /*

<header class="header g2">

    <div class="col">

        <h1 class="header__title h1">Dashboard</h1>

        <ol class="tabs">
            <li class="tabs__tab is--active"><button class="tabs__tab__button">This Week</li>
            <li class="tabs__tab"><button class="tabs__tab__button">All Time</li>
        </ol>

    </div>

    <div class="col">

        <h1 class="header__title h1">Notifications</h1>

    </div>

</header>

*/ ?>

<article class="display g2">

    <div class="col">

        <section class="block">

            <header class="block__header">
                <h1 class="h4">Matches</h1>
            </header>

            <ol class="stream is--matches">

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

            <footer class="block__footer">

                <ul class="block__footer__meta">

                    <li class="block__footer__meta__item">
                        <?= $this->Svg->useit('icon-matches', 'icon-inline block__footer__meta__icon') ?>
                        10 matches
                    </li>

                    <li class="block__footer__meta__item">
                        <?= $this->Svg->useit('icon-players', 'icon-inline block__footer__meta__icon') ?>
                        5 opponents</li>
                </ul>

            </footer>

        </section>


        <section class="block">

            <header class="block__header">
                <h1 class="h4">Box 1 <small class="h4__small">3 weeks left</small></h1>
            </header>

            <ol class="stream is--matches">

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

                        echo $this->element('matchDraw');

                    }

                    $stream = 1;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('matchLoss');

                    }
                ?>

            </ol>

        </section>

    </div>

    <div class="col">

        <section class="block">

            <header class="block__header">
                <h1 class="h4">Notifications</h1>
            </header>

            <ol class="stream is--notifications">
            </ol>

        </section>



        <section class="block">

            <header class="block__header">
                <h1 class="h4">Disputes</h1>
            </header>

            <ol class="stream is--disputes">

                <div class="finder">
                    <div class="finder__display">

                        <figure class="finder__player-photo">

                            <?= $this->Svg->useit('player', 'finder__player-photo__icon') ?>

                            <div class="player-photo__proportion clip-hexagon">


                            </div>

                        </figure>

                        <input class="finder__display__input" type="text" required placeholder="Search Players&hellip;"/>

                        <?= $this->Svg->useit('rarr', 'finder__display__rarr') ?>
                    </div>

                    <li class="finder__display finder__results__result is--selected">
                        <figure class="finder__player-photo finder__results__player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <header>

                            <dt class="player__id__name">Cody Morton</dt>

                        </header>

                        <?= $this->Svg->useit('rarr', 'finder__display__rarr') ?>
                    </li>

                    <ul class="finder__results">

                        <?php

                        for ($k = 0; $k < 10; $k++) :

                        ?>

                        <li class="finder__results__result">
                            <figure class="finder__player-photo finder__results__player-photo">

                                <div class="player-photo__proportion clip-hexagon">

                                    <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                                    <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                                </div>

                            </figure>

                            <header>

                                <dt class="player__id__name">Cody Morton</dt>

                            </header>
                        </li>

                        <?php

                        endfor;

                        ?>

                        

                    </ul>
                </div>

            </ol>

        </section>


    </div>

</article>

<article class="display g2">

    <div class="col">

        <section class="block has--contents">

            <header class="block__header">
                <h1 class="h4__small">Leaderboards</h1>
            </header>

            <div class="block__contents gflex">

                <div class="col">

                    <h2 class="h4 stream__title"><a href="#">This Week</a></h2>

                    <ol class="stream is--players">

                        <?=$this->element('playerWeek', [
                                'number' => 1,
                                'rating' => 'high',
                                'bandit' => 'no',
                                'sibling' => 1
                            ])?>

                        <?=$this->element('playerWeek', [
                                'number' => 2,
                                'rating' => 'medium',
                                'bandit' => 'no',
                                'sibling' => 0
                            ])?>

                        <?=$this->element('playerWeek', [
                                'number' => 3,
                                'rating' => 'low',
                                'bandit' => 'no',
                                'sibling' => 1
                            ])?>


                    </ol>

                </div>

                <div class="col">

                    <h2 class="h4 stream__title"><a href="#">All Time</a></h2>

                    <ol class="stream is--players">

                        <?=$this->element('player', [
                                'number' => 1,
                                'rating' => 'high',
                                'bandit' => 'yes',
                                'sibling' => 2
                            ])?>

                        <?=$this->element('player', [
                                'number' => 2,
                                'rating' => 'medium',
                                'bandit' => 'no',
                                'sibling' => 1
                            ])?>

                        <?=$this->element('player', [
                                'number' => 3,
                                'rating' => 'low',
                                'bandit' => 'no',
                                'sibling' => 0
                            ])?>

                    </ol>

                </div>

            </div>

        </section>

    </div>

</article>