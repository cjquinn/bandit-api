<header class="header g3">

    <div class="col">

        <h1 class="header__title h1">This Week</h1>

    </div>

    <div class="col">

        <h1 class="header__title h1">All Time</h1>

    </div>

    <nav class="header__select">
        <h1 class="h2">Leaderboards</h1>
        
        <label>
            <select>
                <option disabled>Leaderboards:</option>
                <option selected>This Week</option>
                <option>All Time</option>
            </select>
            <?= $this->Svg->useit('rarr', 'rarr header__select__rarr') ?>
        </label>
    </nav>

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