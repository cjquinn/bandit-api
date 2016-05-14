<li class="player is--sibling<?=$sibling?> block__slab">

    <dl class="player__id">

        <figure class="player__player-photo player-photo">

            <?php

                if ($bandit == 'yes') {

            ?>

            <?= $this->Svg->useit('brand-knot', 'player-photo__knot__svg') ?>

            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?> is--level100"></div>
                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

            </div>

            <?php

                } else {

            ?>

            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

            </div>

            <?php

                }

            ?>

            

        </figure>

        <header>

            <dt class="player__id__name">Cody Morton</dt>

            <dd class="player__id__stats">
                <?php if ($bandit == 'yes') { echo '<span class="player__id__level is--level100">Bandit</span> '; } ?>
                <span class="player__id__level is--level55">Ninja</span>
                <span class="player__id__rating"><?= $this->Svg->useit('icon-rating', 'match__player__rating__icon')?><?= $this->Rating->display($rating) ?>
            </dd>

        </header>

    </dl>

    <aside class="player__number h3"><small class="h3__small">#</small><?=$number;?></aside>

</li>