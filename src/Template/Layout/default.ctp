<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>

    <title><?= $this->fetch('title') ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000207">

    <link rel="shortcut icon" href="/favicon.ico?2=yes">
    <link rel="shortcut icon" sizes="192x192" href="/favicon@196w.png">
    <link rel="shortcut icon" sizes="128x128" href="/favicon@128w.png">
    <link rel="apple-touch-icon" sizes="128x128" href="/favicon@128w.png">
    <link rel="apple-touch-icon-precomposed" sizes="128x128" href="/favicon@128w.png">

    <link rel="stylesheet" type="text/css" href="/css/style.css" media="all" />

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <?= $this->fetch('content') ?>

    <?= $this->fetch('scriptBottom') ?>
</body>
</html>
