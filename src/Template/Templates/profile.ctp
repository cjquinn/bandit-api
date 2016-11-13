<header class="header g2">

    <div class="col">

        <dl class="header__title galignitemscenter">

            <figure class="player__player-photo player-photo">

                <?= $this->Svg->useit('brand-knot', 'player-photo__knot__svg') ?>

                <dd class="player-photo__proportion clip-hexagon">

                    <div class="player-photo__level is--level54 is--level100"></div>
                    <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" alt="Photo 1" class="player-photo__image" />

                </dd>

            </figure>

            <dt class="h1">Ronald McKinney</dt>

        </dl>

        <ol class="tabs">
            <li class="tabs__tab is--active"><button class="tabs__tab__button">Stats</button></li>
            <li class="tabs__tab"><button class="tabs__tab__button">Matches</button></li>
            <li class="tabs__tab"><button class="tabs__tab__button">Disputes</button></li>
            <li class="tabs__tab"><button class="tabs__tab__button">Notifications</button></li>
            <li class="tabs__tab"><button class="tabs__tab__button">Preferences</button></li>
        </ol>

    </div>


</header>

<article class="display g2">

    <div class="col">

        <section class="block has--contents">

            <header class="block__header">

                <ol class="tabs block__tabs">
                    <li class="tabs__tab is--active"><button class="tabs__tab__button">Rating<span class="tabs__tab__sub">9870</span></button></li>
                    <li class="tabs__tab"><button class="tabs__tab__button">Win Ratio</button></li>
                </ol>

                <dl class="key">

                    <li class="key__stat">
                        <dt><?= $this->Svg->useit('icon-rating', 'match__player__rating__icon icon-inline')?>Highest:</dt> <dd>10240</dd>
                    </li>

                    <li class="key__stat">
                        <dt><?= $this->Svg->useit('icon-rating', 'match__player__rating__icon icon-inline')?>Lowest:</dt> <dd>6240</dd>
                    </li>

                </dl>

                <div class="block__contents">

                </div>

            </header>

        </section>

    </div>

</article>


<article class="display g2">

    <div class="col">

        <?=$this->element('levelProgress')?>

    </div>

    <div class="col">

        <section class="stats block has--contents">

            <header class="block__header">
                <h1 class="h4__small">Stats</h1>
                <h2 class="h4">All-Time</h2>
            </header>

            <div class="block__contents">

                <div class="block__base">

                    <ul>

                        <li>
                            <h4 class="h6">Games</h4>
                            <p class="h3">502</p>
                            <p>450w 52l</p>
                        </li>

                    </ul>                    

                </div>

            </div>

        </section>

    </div>

</article>




<article class="display g2">

    <div class="col">

        <?=$this->element('leaderboardSummary')?>

    </div>

</article>