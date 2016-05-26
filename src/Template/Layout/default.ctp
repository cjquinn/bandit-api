<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000207">

    <title><?= $this->fetch('title') ?></title>

    <link rel="stylesheet" type="text/css" href="/css/style.css" media="all" />

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <script src="https://use.typekit.net/zgp3lhc.js"></script>
    <script>try{Typekit.load({ async: true });}catch(e){}</script>

</head>
<body>

<?= $this->Flash->render() ?>

<svg class="svg-library" style="display: none;">

    <defs>

        <?= $this->Svg->display('brand/logo') ?>
        <?= $this->Svg->display('brand/knot') ?>

        <?= $this->Svg->display('rarr') ?>
        <?= $this->Svg->display('player') ?>

        <?= $this->Svg->display('icons/matches') ?>
        <?= $this->Svg->display('icons/players') ?>
        <?= $this->Svg->display('icons/rating') ?>

        <?= $this->Svg->display('menu') ?>
        <?= $this->Svg->display('plus') ?>
        <?= $this->Svg->display('tick') ?>
        <?= $this->Svg->display('cross') ?>

    </defs>

</svg>

<div class="app">

    <div class="g1">

    	<nav class="menu col">

            <a href="/" class="menu__logo">

                <?= $this->Svg->useit('brand-logo', 'menu__logo__svg'); ?>
                
            </a>

            <button class="menu__toggle"><?= $this->Svg->useit('menu', 'menu__toggle__svg'); ?></button>

            <div class="menu__whole">

                <button class="menu__toggle"><?= $this->Svg->useit('menu', 'menu__toggle__svg'); ?></button>

        		<ol class="menu__list">

        			<li class="menu__list__item is--active">
        				<a href="/templates/dashboard" class="menu__list__link is--active">Dashboard</a>
        			</li>

        			<li class="menu__list__item">
        				<a href="/templates/leaderboards" class="menu__list__link">Leaderboards</a>
        			</li>

        			<li class="menu__list__item">
        				<a href="/templates/boxleague" class="menu__list__link">Box League</a>
        			</li>

        			<li class="menu__list__item">
        				<a href="/" class="menu__list__link">Players</a>
        			</li>

        			<li class="menu__list__item">
        				<a href="/templates/profile" class="menu__list__link">My Profile</a>
        			</li>

        		</ol>

                <button class="button menu__button is--full">
                    <div class="button__icon">
                        <figure class="button__icon__clip"><?= $this->Svg->useit('plus', 'icon-plus button__icon__svg') ?></figure>
                    </div>Add Wins
                </button>

            </div>

    	</nav>

        <article class="view col">

    	    <?= $this->fetch('content') ?>

        </article>

    </div><?/* .g1 */?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="/js/scripts.js"></script>

</body>
</html>
