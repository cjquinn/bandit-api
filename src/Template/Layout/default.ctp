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

                </div>

                <div class="col">

                    <h1 class="header__title h1">Notifications</h1>

                </div>

            </div>

            <article class="display g2">

                <section class="col">

                    <section class="block">

                        <header class="block__header">
                            <h1 class="h2">Matches</h1>
                        </header>

                        <ol class="stream">
                            <li class="match g3">


                                <div class="match__player is--winner col gflex">

                                    <dl>

                                        <dt class="match__player__name">Russell</dt>
                                        <dd class="match__player__rating">6085</dd>

                                    </dl>

                                    <figure class="match__player__player-photo">
                                        <div class="player-photo__proportion">
                                            <img src="/img/photos/photo1.jpg" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                </div>

                                <aside class="match__score col">
                                    3<span class="match__score__hyphen">-</span>0
                                </aside>

                                <div class="match__player is--loser col gflex">

                                    <figure class="match__player__player-photo player-photo">
                                        <div class="player-photo__proportion">
                                            <img src="/img/photos/photo1.jpg" alt="Photo 1" class="player-photo__image clip-hexagon" />
                                        </div>
                                    </figure>

                                    <dl>

                                        <dt class="match__player__name">Russell</dt>

                                        <dd class="match__player__rating">6085</dd>

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

</body>
</html>