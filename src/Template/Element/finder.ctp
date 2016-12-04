<div class="finder">
    <div class="finder__display">

        <figure class="finder__player-photo">

            <?= $this->Svg->useit('player', 'finder__player-photo__icon') ?>

            <div class="player-photo__proportion clip-hexagon">


            </div>

        </figure>

        <input class="finder__display__input" type="text" required placeholder="Search Players&hellip;"/>

        <?= $this->Svg->useit('rarr', 'finder__display__rarr') ?>

        <?= $this->Svg->useit('cross', 'finder__display__close close'); ?>
        
    </div>

    <?php /*
    <li class="finder__display finder__results__result is--selected">
        <figure class="finder__player-photo finder__results__player-photo">

            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

            </div>

        </figure>

        <header>

            <dt class="player__id__name">Cody Morton</dt>

        </header>

        <?= $this->Svg->useit('rarr', 'finder__display__rarr') ?>
    </li>
    */ ?>

    <ul class="finder__results">

        <?php

        for ($k = 0; $k < 10; $k++) :

        ?>

        <li class="finder__results__result">
            <figure class="finder__player-photo finder__results__player-photo">

                <div class="player-photo__proportion clip-hexagon">

                    <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                    <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                </div>

            </figure>

            <header>

                <dt class="player__id__name">Cody Morton</dt>

            </header>
        </li>

        <?php

        endfor;

        ?>

        

    </ul>
</div>