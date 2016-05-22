<header class="header g2">

    <div class="col">

        <h1 class="header__title h1">Season <small class="h1__small">Ends June 16th</small></h1>

        <ol class="tabs">
            <li class="tabs__tab"><button class="tabs__tab__button">Build</li>
            <li class="tabs__tab"><button class="tabs__tab__button">My Matches</li>
            <li class="tabs__tab is--active"><button class="tabs__tab__button">Box 1</li>
            <li class="tabs__tab"><button class="tabs__tab__button">Box 2</li>
            <li class="tabs__tab"><button class="tabs__tab__button">Box 3</li>
            <li class="tabs__tab"><button class="tabs__tab__button">Box 4</li>
            <li class="tabs__tab"><button class="tabs__tab__button">Box 5</li>
        </ol>

    </div>

</header>

<article class="display g2">

    <div class="col">

        <section class="block">

            <header class="block__header">
                <h1 class="h4">Matches</h1>
            </header>

            <ol class="stream is--matches">

                <?php

                    for ($k = 0; $k < 3; $k++) {

                        echo $this->element('match');
                        echo $this->element('matchLoss');

                    }

                ?>

            </ol>

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

                            <?= $this->Svg->useit('brand-knot', 'player-photo__knot__svg') ?>

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level55 is--level100"></div>
                                <img src="http://userphoto.russellbishop.co.uk/get.php?id=31" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <header>

                            <dt class="player__id__name">Cody Morton</dt>
                            <dd class="player__id__stats">6 wins 0 losses</dd>

                        </header>

                    </dl>

                    <aside class="player__number h3">28<small class="h3__small">pts</small></aside>

                </li>

                <li class="player block__slab">

                    <dl class="player__id">

                        <figure class="player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <header>

                            <dt class="player__id__name">Cody Morton</dt>
                            <dd class="player__id__stats">4 wins 0 losses</dd>

                        </header>

                    </dl>

                    <aside class="player__number h3">19<small class="h3__small">pts</small></aside>

                </li>

                <li class="player block__slab">

                    <dl class="player__id">

                        <figure class="player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <header>

                            <dt class="player__id__name">Cody Morton</dt>
                            <dd class="player__id__stats">4 wins 0 losses</dd>

                        </header>

                    </dl>

                    <aside class="player__number h3">14<small class="h3__small">pts</small></aside>

                </li>

                <li class="player block__slab">

                    <dl class="player__id">

                        <figure class="player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <header>

                            <dt class="player__id__name">Cody Morton</dt>
                            <dd class="player__id__stats">0 wins 1 losses</dd>

                        </header>

                    </dl>

                    <aside class="player__number h3">5<small class="h3__small">pts</small></aside>

                </li>

                <li class="player block__slab">

                    <dl class="player__id">

                        <figure class="player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <header>

                            <dt class="player__id__name">Cody Morton</dt>
                            <dd class="player__id__stats">Waiting for matches&hellip;</dd>

                        </header>

                    </dl>

                </li>

                <li class="player block__slab">

                    <dl class="player__id">

                        <figure class="player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <header>

                            <dt class="player__id__name">Cody Morton</dt>
                            <dd class="player__id__stats">Waiting for matches&hellip;</dd>

                        </header>

                    </dl>

                </li>

            </ol>

        </section>


    </div>

</article>