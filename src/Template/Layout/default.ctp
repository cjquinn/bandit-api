<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->fetch('title') ?></title>

    <link rel="stylesheet" type="text/css" href="/css/style.css" media="all" />

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<?= $this->Flash->render() ?>

<figure class="svg-library" style="display:none;">
    <?= $this->Svg->display('brand/logo') ?>
    <?= $this->Svg->display('player/level') ?>
</figure>

<div class="app">

    <div class="g1">

    	<nav class="menu col">

            <a href="/" class="menu__logo">
                <svg class="menu__logo__svg"><use class="menu__logo__use" xlink:href="#brand-logo" /></svg>
            </a>

    		<ol class="menu__list">

    			<li class="menu__list__item is--active">
    				<a href="/" class="menu__list__link is--active">Dashboard</a>
    			</li>

    			<li class="menu__list__item">
    				<a href="/" class="menu__list__link">Leaderboards</a>
    			</li>

    			<li class="menu__list__item">
    				<a href="/" class="menu__list__link">Box League</a>
    			</li>

    			<li class="menu__list__item">
    				<a href="/" class="menu__list__link">Players</a>
    			</li>

    			<li class="menu__list__item">
    				<a href="/" class="menu__list__link">My Profile</a>
    			</li>

    		</ol>

            <a class="button menu__button" href="/">
                Add Wins
            </a>

    	</nav>

        <article class="view col">

            <div class="header g2">

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

            </div>

            <article class="display g2">

                <section class="col">

                    <section class="block">

                        <header class="block__header">
                            <h1 class="h3">Monday <small class="h3__small">25th April</small></h1>
                        </header>

                        <ol class="stream is--matches">

                            <?php

                                $stream = 1;

                                for ($k = 0; $k < $stream; $k++) {

                            ?>

                            <li class="match is--winner-first gflex">

                                <div class="match__player is--winner is--level50 col">

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">RB</dt>
                                        <dd class="match__player__rating">6085</dd>

                                    </dl>

                                    <figure class="match__player__player-photo">
                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=1" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                </div>

                                <aside class="match__score">3<span class="match__score__hyphen">-</span>0</aside>

                                <div class="match__backgrounds"></div>

                                <div class="match__player is--loser is--level49 col">

                                    <figure class="match__player__player-photo player-photo">

                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=2" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>

                                    </figure>

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">JM</dt>
                                        <dd class="match__player__rating">5675</dd>

                                    </dl>                                    

                                </div>

                            </li> 

                            <?php } ?>

                            <li class="match is--loser-first gflex">

                                <div class="match__player is--loser is--level52 col">

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">AJ</dt>
                                        <dd class="match__player__rating">7015</dd>

                                    </dl>

                                    <figure class="match__player__player-photo">
                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=3" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                </div>

                                <aside class="match__score">1<span class="match__score__hyphen">-</span>4</aside>

                                <div class="match__backgrounds"></div>

                                <div class="match__player is--winner is--level52 col">

                                    <figure class="match__player__player-photo player-photo">

                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=4" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>

                                    </figure>

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">TS</dt>
                                        <dd class="match__player__rating">6635</dd>

                                    </dl>                                    

                                </div>

                            </li>

                            <li class="match is--draw gflex">

                                <div class="match__player is--draw col">

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">MH</dt>
                                        <dd class="match__player__rating">4820</dd>

                                    </dl>

                                    <figure class="match__player__player-photo">
                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=7" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                </div>

                                <aside class="match__score">2<span class="match__score__hyphen">-</span>2</aside>

                                <div class="match__backgrounds"></div>

                                <div class="match__player is--draw col">

                                    <figure class="match__player__player-photo player-photo">

                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=6" alt="Photo 1" class="player-photo__image clip-hexagon" />
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

                </section>

                <section class="col">

                    <section class="block">

                        <header class="block__header">
                            <h1 class="h3">Monday <small class="h3__small">25th April</small></h1>
                        </header>

                        <ol class="stream is--matches">

                            <?php

                                $stream = 1;

                                for ($k = 0; $k < $stream; $k++) {

                            ?>

                            <li class="match is--winner-first gflex">

                                <div class="match__player is--winner is--level50 col">

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">RB</dt>
                                        <dd class="match__player__rating">6085</dd>

                                    </dl>

                                    <figure class="match__player__player-photo">
                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=1" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                </div>

                                <aside class="match__score">3<span class="match__score__hyphen">-</span>0</aside>

                                <div class="match__backgrounds"></div>

                                <div class="match__player is--loser is--level49 col">

                                    <figure class="match__player__player-photo player-photo">

                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=2" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>

                                    </figure>

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">JM</dt>
                                        <dd class="match__player__rating">5675</dd>

                                    </dl>                                    

                                </div>

                            </li> 

                            <?php } ?>

                            <li class="match is--loser-first gflex">

                                <div class="match__player is--loser is--level52 col">

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">AJ</dt>
                                        <dd class="match__player__rating">7015</dd>

                                    </dl>

                                    <figure class="match__player__player-photo">
                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=3" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                </div>

                                <aside class="match__score">1<span class="match__score__hyphen">-</span>4</aside>

                                <div class="match__backgrounds"></div>

                                <div class="match__player is--winner is--level52 col">

                                    <figure class="match__player__player-photo player-photo">

                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=4" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>

                                    </figure>

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">TS</dt>
                                        <dd class="match__player__rating">6635</dd>

                                    </dl>                                    

                                </div>

                            </li>

                            <li class="match is--draw gflex">

                                <div class="match__player is--draw col">

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">MH</dt>
                                        <dd class="match__player__rating">4820</dd>

                                    </dl>

                                    <figure class="match__player__player-photo">
                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=7" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                </div>

                                <aside class="match__score">2<span class="match__score__hyphen">-</span>2</aside>

                                <div class="match__backgrounds"></div>

                                <div class="match__player is--draw col">

                                    <figure class="match__player__player-photo player-photo">

                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=6" alt="Photo 1" class="player-photo__image clip-hexagon" />
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

                </section>

            </article>

            <div class="header g2">

                <div class="col">

                    <h1 class="header__title h1">Season <small class="h1__small">Ends June 16th</small></h1>

                    <ol class="tabs">
                        <li class="tabs__tab is--active"><button class="tabs__tab__button">My Matches</li>
                        <li class="tabs__tab"><button class="tabs__tab__button">Box 1</li>
                        <li class="tabs__tab"><button class="tabs__tab__button">Box 2</li>
                        <li class="tabs__tab"><button class="tabs__tab__button">Box 3</li>
                        <li class="tabs__tab"><button class="tabs__tab__button">Box 4</li>
                        <li class="tabs__tab"><button class="tabs__tab__button">Box 5</li>
                    </ol>

                </div>

            </div>

            <article class="display g2">

                <section class="col">

                    <section class="block">

                        <header class="block__header">
                            <h1 class="h3">Monday <small class="h3__small">25th April</small></h1>
                        </header>

                        <ol class="stream is--matches">

                            <?php

                                $stream = 1;

                                for ($k = 0; $k < $stream; $k++) {

                            ?>

                            <li class="match is--winner-first gflex">

                                <div class="match__player is--winner is--level50 col">

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">RB</dt>
                                        <dd class="match__player__rating">6085</dd>

                                    </dl>

                                    <figure class="match__player__player-photo">
                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=1" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                </div>

                                <aside class="match__score">3<span class="match__score__hyphen">-</span>0</aside>

                                <div class="match__backgrounds"></div>

                                <div class="match__player is--loser is--level49 col">

                                    <figure class="match__player__player-photo player-photo">

                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=2" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>

                                    </figure>

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">JM</dt>
                                        <dd class="match__player__rating">5675</dd>

                                    </dl>                                    

                                </div>

                            </li> 

                            <?php } ?>

                            <li class="match is--loser-first gflex">

                                <div class="match__player is--loser is--level52 col">

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">AJ</dt>
                                        <dd class="match__player__rating">7015</dd>

                                    </dl>

                                    <figure class="match__player__player-photo">
                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=3" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                </div>

                                <aside class="match__score">1<span class="match__score__hyphen">-</span>4</aside>

                                <div class="match__backgrounds"></div>

                                <div class="match__player is--winner is--level52 col">

                                    <figure class="match__player__player-photo player-photo">

                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=4" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>

                                    </figure>

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">TS</dt>
                                        <dd class="match__player__rating">6635</dd>

                                    </dl>                                    

                                </div>

                            </li>

                            <li class="match is--draw gflex">

                                <div class="match__player is--draw col">

                                    <dl class="match__player__id">

                                        <dt class="match__player__name">MH</dt>
                                        <dd class="match__player__rating">4820</dd>

                                    </dl>

                                    <figure class="match__player__player-photo">
                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=7" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                </div>

                                <aside class="match__score">2<span class="match__score__hyphen">-</span>2</aside>

                                <div class="match__backgrounds"></div>

                                <div class="match__player is--draw col">

                                    <figure class="match__player__player-photo player-photo">

                                        <div class="player-photo__proportion">
                                            <svg class="player-photo__level__svg"><use class="player-photo__level__use" xlink:href="#player-level" /></svg>
                                            <img src="http://userphoto.localhost/get.php?id=6" alt="Photo 1" class="player-photo__image clip-hexagon" />
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

                </section>

                <section class="col">


                </section>

            </article>

    	    <?= $this->fetch('content') ?>

        </article>

    </div><?/* .g1 */?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script>
</script>

</body>
</html>