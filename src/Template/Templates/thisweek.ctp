<header class="header g2">

    <div class="col">

        <h1 class="header__title h1">Matches</h1>

        <ol class="tabs">
        	<li class="tabs__tab is--disabled" title="Previous Day"><button class="tabs__tab__button"><svg class="icon-inline rarr__svg tabs__tab__larr is--flippedX"><use class="larr__use tabs__tab__larr__use" xlink:href="#rarr" /></svg></li>
            <li class="tabs__tab is--active"><button class="tabs__tab__button">Monday</li>
            <li class="tabs__tab" title="Next Day"><button class="tabs__tab__button"><svg class="icon-inline rarr__svg tabs__tab__rarr"><use class="rarr__use tabs__tab__rarr__use" xlink:href="#rarr" /></svg></li>
        </ol>

    </div>

    <div class="col">

        <h1 class="header__title h1">Leaderboard</h1>

        <ol class="tabs">
            <li class="tabs__tab is--active"><button class="tabs__tab__button">This Week</li>
            <li class="tabs__tab"><button class="tabs__tab__button">All Time</li>
        </ol>

    </div>

</header>

<article class="display g2">

    <div class="col">

        <section class="block">

            <header class="block__header">
                <h1 class="h4">Monday <small class="h3__small">25th April</small></h1>
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

            <footer class="block__footer">

                <ul class="block__footer__meta">

                    <li class="block__footer__meta__item">

                        <?= $this->Svg->useit('icon-matches', 'block__footer__icon'); ?>

                        6 Matches

                    </li>


                </ul>

            </footer>

        </section>

    </div>

    <div class="col">

        <section class="block">

            <header class="block__header">
                <h1 class="h4">Leaderboard</h1>
            </header>

            <ol class="stream is--players">

                <li class="player block__slab">

                    <dl class="player__id">

                        <figure class="player__player-photo player-photo">

                        	<svg class="player-photo__knot__svg"><use class="player-photo__knot__use" xlink:href="#brand-knot" /></svg>

                            <div class="player-photo__proportion clip-hexagon">

                            	<div class="player-photo__level is--level55 is--level100"></div>
                                <img src="http://userphoto.russellbishop.co.uk/get.php?id=31" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <header>

                            <dt class="player__id__name">Cody Morton</dt>
                            <dd class="player__id__stats"><span class="player__id__level is--level100">Bandit</span> <span class="player__id__level is--level55">Ninja</span> <span class="player__id__rating">8940</dd>

                        </header>

                    </dl>

                    <aside class="player__number h3"><small class="h3__small">#</small>1</aside>

                </li>

                <li class="player block__slab">

                    <dl class="player__id">

                        <figure class="player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level54"></div>
                                <img src="http://userphoto.russellbishop.co.uk/get.php?id=60" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <header>

                            <dt class="player__id__name">Jeremy Clarkson</dt>
                            <dd class="player__id__stats"><span class="player__id__level is--level54">Assassin</span> <span class="player__id__rating">7995</dd>

                        </header>

                    </dl>

                    <aside class="player__number h3"><small class="h3__small">#</small>2</aside>

                </li>

            </ol>

            <footer class="block__footer">

                <ul class="block__footer__meta">

                    <li class="block__footer__meta__item">

                        <?= $this->Svg->useit('icon-matches', 'block__footer__icon'); ?>

                    </li>


                </ul>

            </footer>

        </section>


    </div>

</article>