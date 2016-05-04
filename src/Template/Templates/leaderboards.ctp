<div class="header g2">

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

</div>

<article class="display g2">

    <div class="col">

        <section class="block">

            <header class="block__header">
                <h1 class="h4">Monday <small class="h3__small">25th April</small></h1>
            </header>

            <ol class="stream is--matches">

                <?php

                    $stream = 1;

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

                                <div class="player-photo__level is--level50"></div>
                                <img src="http://userphoto.localhost/get.php?id=1" alt="Photo 1" class="player-photo__image" />

                            </div>
                        </figure>

                    </div>

                    <aside class="match__score h3">3<span class="match__score__hyphen">-</span>0</aside>

                    <div class="match__backgrounds"></div>

                    <div class="match__player is--loser col">

                        <figure class="match__player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="player-photo__level is--level49"></div>
                                <img src="http://userphoto.localhost/get.php?id=2" alt="Photo 1" class="player-photo__image" />

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
                                <img src="http://userphoto.localhost/get.php?id=60" alt="Photo 1" class="player-photo__image" />

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

        </section>


    </div>

</article>