<li class="match block__slab is--draw gflex">

    <? /*
        first player
    */ ?>
    <div class="match__player is--first col">

        <dl class="match__player__id">

            <dt class="match__player__name" data-initials="<?= $this->Initial->display() ?>"><span class="match__player__name__full">Marley Jenkins</span></dt>
            <dd class="match__player__rating"><?= $this->Svg->useit('icon-rating', 'match__player__rating__icon')?><?= $this->Rating->display('low') ?></dd>

        </dl>

        <figure class="match__player__player-photo player-photo">
            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

            </div>
        </figure>

    </div>

    <? /*
        second player
    */ ?>
    <div class="match__player is--second col">

        <figure class="match__player__player-photo player-photo">

            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

            </div>

        </figure>

        <dl class="match__player__id">

            <dt class="match__player__name" data-initials="<?= $this->Initial->display() ?>"><span class="match__player__name__full">Marley Jenkins</span></dt>
            <dd class="match__player__rating"><?= $this->Svg->useit('icon-rating', 'match__player__rating__icon')?><?= $this->Rating->display('low') ?></dd>

        </dl>                                    

    </div>

    <?php /*
        match score
    */
    $score = rand(0, 4); ?>
    <aside class="score match__score h3"><?=$score?><span class="score__hyphen match__score__hyphen">-</span><?=$score?></aside>


    <? /*
        win/loss glow backgrounds
    */ ?>
    <div class="match__backgrounds"></div>

</li>