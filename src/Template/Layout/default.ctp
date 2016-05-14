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

    </defs>

</svg>

<div class="app">

    <div class="g1">

    	<nav class="menu col">

            <a href="/" class="menu__logo">
                <svg class="menu__logo__svg" viewBox="0 0 129 95"><use xlink:href="#brand-logo" /></svg>
            </a>

            <h6 class="menu__current">Dashboard</h6>

            <button class="menu__toggle">Menu</button>

            <div class="menu__whole">

        		<ol class="menu__list">

        			<li class="menu__list__item is--active">
        				<a href="/templates/dashboard" class="menu__list__link <?php /* is--active */ ?>">Dashboard</a>
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
        				<a href="/" class="menu__list__link">My Profile</a>
        			</li>

        		</ol>

                <a class="button menu__button" href="/">Add Wins</a>

            </div>

    	</nav>

        <article class="view col">

    	    <?= $this->fetch('content') ?>

        </article>

    </div><?/* .g1 */?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script>
</script>

</body>
</html>
