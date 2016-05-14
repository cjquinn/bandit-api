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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script>

$(function() {

});

</script>

</body>
</html>
