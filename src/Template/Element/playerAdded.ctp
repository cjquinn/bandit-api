<?php

    if (isset($name)) {
        $theName = $name;
    } else {
        $theName = 'Jeremy Knowles';
    }

?>

<li class="player <?php if ($sibling != '0') { echo 'is--sibling is--sibling' . $sibling; } ?> block__slab">

    <dl class="player__id">

        <figure class="player__player-photo player-photo">

            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

            </div>            

        </figure>

        <header>

            <dt class="player__id__name"><?=$theName?></dt>

            <dd class="player__id__stats">
                <span class="player__id__action">Added <?=rand(1, 6)?> games against you today.</span>
            </dd>

        </header>

    </dl>

</li>