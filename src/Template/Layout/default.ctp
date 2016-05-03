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
    <?= $this->Svg->display('brand/knot') ?>
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
    				<a href="/" class="menu__list__link <?php /* is--active */ ?>">Dashboard</a>
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

            <a class="button menu__button" href="/">
                Add Wins
            </a>

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