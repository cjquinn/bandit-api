<li class="player is--opponent block__slab">

    <dl class="player__id">

        <figure class="player__player-photo player-photo">

            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

            </div>

        </figure>

        <header>

            <dt class="player__id__name">Cody Morton</dt>
            <dd class="player__id__stats"><a href="mailto:me@you.com">cody.morton@gmail.com</a></dd>

        </header>

    </dl>

    <aside class="box-result">
        <button class="button is--full">
            <div class="button__icon">
                <figure class="button__icon__clip"><?= $this->Svg->useit('plus', 'icon-plus button__icon__svg') ?></figure>
            </div>Add Result
        </button>
    </aside>

</li>