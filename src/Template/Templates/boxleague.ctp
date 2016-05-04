<header class="header g2">

    <div class="col">

        <h1 class="header__title h1">Season <small class="h1__small">Ends June 16th</small></h1>

        <ol class="tabs">
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

                    $stream = 6;

                    for ($k = 0; $k < $stream; $k++) {

                ?>

                <li class="match block__slab is--winner-first gflex">

                    <div class="match__player is--winner col">

                        <dl class="match__player__id">

                            <dt class="match__player__name">RB</dt>
                            <dd class="match__player__rating">6085</dd>

                        </dl>

                        <figure class="match__player__player-photo">
                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                                <img src="http://userphoto.localhost/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                            </div>
                        </figure>

                    </div>

                    <aside class="match__score h3">3<span class="match__score__hyphen">-</span>0</aside>

                    <div class="match__backgrounds"></div>

                    <div class="match__player is--loser col">

                        <figure class="match__player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                                <img src="http://userphoto.localhost/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <dl class="match__player__id">

                            <dt class="match__player__name">JM</dt>
                            <dd class="match__player__rating">5675</dd>

                        </dl>                                    

                    </div>

                </li> 

                <?php } ?>

                <li class="match block__slab is--loser-first gflex">

                    <div class="match__player is--loser col">

                        <dl class="match__player__id">

                            <dt class="match__player__name">AJ</dt>
                            <dd class="match__player__rating">7015</dd>

                        </dl>

                        <figure class="match__player__player-photo">
                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level52"></div>
                                <img src="http://userphoto.localhost/get.php?id=3" alt="Photo 1" class="player-photo__image" />

                            </div>
                        </figure>

                    </div>

                    <aside class="match__score h3">1<span class="match__score__hyphen">-</span>4</aside>

                    <div class="match__backgrounds"></div>

                    <div class="match__player is--winner col">

                        <figure class="match__player__player-photo player-photo">

                            <svg class="player-photo__knot__svg"><use class="player-photo__knot__use" xlink:href="#brand-knot" /></svg>

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level55 is--level100"></div>
                                <img src="http://userphoto.localhost/get.php?id=4" alt="Photo 1" class="player-photo__image" />
                            </div>

                        </figure>

                        <dl class="match__player__id">

                            <dt class="match__player__name">TS</dt>
                            <dd class="match__player__rating">6635</dd>

                        </dl>                                    

                    </div>

                </li>

                <li class="match block__slab is--draw gflex">

                    <div class="match__player is--draw col">

                        <dl class="match__player__id">

                            <dt class="match__player__name">MH</dt>
                            <dd class="match__player__rating">4820</dd>

                        </dl>

                        <figure class="match__player__player-photo">
                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level54"></div>
                                <img src="http://userphoto.localhost/get.php?id=7" alt="Photo 1" class="player-photo__image" />

                            </div>
                        </figure>

                    </div>

                    <aside class="match__score h3">2<span class="match__score__hyphen">-</span>2</aside>

                    <div class="match__backgrounds"></div>

                    <div class="match__player is--draw col">

                        <figure class="match__player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level54"></div>
                                <img src="http://userphoto.localhost/get.php?id=6" alt="Photo 1" class="player-photo__image" />

                            </div>

                        </figure>

                        <dl class="match__player__id">

                            <dt class="match__player__name">KL</dt>
                            <dd class="match__player__rating">4910</dd>

                        </dl>                                    

                    </div>

                </li>

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

                            <svg class="player-photo__knot__svg"><use class="player-photo__knot__use" xlink:href="#brand-knot" /></svg>

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level55 is--level100"></div>
                                <img src="http://userphoto.localhost/get.php?id=31" alt="Photo 1" class="player-photo__image" />

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
                                <img src="http://userphoto.localhost/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

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
                                <img src="http://userphoto.localhost/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

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
                                <img src="http://userphoto.localhost/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

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
                                <img src="http://userphoto.localhost/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

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
                                <img src="http://userphoto.localhost/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

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