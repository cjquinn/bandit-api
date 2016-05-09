<li class="match block__slab is--loser-first gflex">

    <? /*
        first player
    */ ?>
    <div class="match__player is--first is--loser col">

        <dl class="match__player__id">

            <dt class="match__player__name"><?= $this->Initial->display() ?></dt>
            <dd class="match__player__rating"><?= $this->Rating->display('high') ?></dd>

        </dl>

        <figure class="match__player__player-photo player-photo">
            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                <img src="http://userphoto.localhost/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

            </div>
        </figure>

    </div>

    <? /*
        second player
    */ ?>
    <div class="match__player is--second is--winner col">

        <figure class="match__player__player-photo player-photo">

            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                <img src="http://userphoto.localhost/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

            </div>

        </figure>

        <dl class="match__player__id">

            <dt class="match__player__name"><?= $this->Initial->display() ?></dt>
            <dd class="match__player__rating"><?= $this->Rating->display('low') ?></dd>

        </dl>                                    

    </div>

    <? /*
        match score
    */ ?>
    <aside class="match__score h3"><?=rand(0, 1)?><span class="match__score__hyphen">-</span><?=rand(2, 5)?></aside>


    <? /*
        win/loss glow backgrounds
    */ ?>
    <div class="match__backgrounds"></div>

</li>