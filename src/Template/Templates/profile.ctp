<header class="header g2">

    <div class="col">

        <dl class="header__title galignitemscenter">

            <figure class="player__player-photo player-photo">

                <?= $this->Svg->useit('brand-knot', 'player-photo__knot__svg') ?>

                <dd class="player-photo__proportion clip-hexagon">

                    <div class="player-photo__level is--level<?=rand(47, 55)?> is--level100"></div>
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

            <div class="block__contents">

                <ol class="tabs">
                    <li class="tabs__tab is--active"><button class="tabs__tab__button">Rating</button></li>
                    <li class="tabs__tab"><button class="tabs__tab__button">Win Ratio</button></li>
                </ol>

                <aside class="key">

                    <dl class="block__footer__meta">

                        <li class="block__footer__meta__item">
                            <dt>Highest:</dt> <?= $this->Svg->useit('icon-rating', 'match__player__rating__icon')?><dd>10240</dd>
                        </li>

                        <li class="block__footer__meta__item">
                            <dt>Lowest:</dt> <?= $this->Svg->useit('icon-rating', 'match__player__rating__icon')?><dd>6240</dd>
                        </li>
                    </dl>

                </aside>

                <ol class="stream is--players">

                    <?=$this->element('playerWeek', [
                            'number' => 1,
                            'rating' => 'high',
                            'bandit' => 'no',
                            'sibling' => 1
                        ])?>

                    <?=$this->element('playerWeek', [
                            'number' => 2,
                            'rating' => 'medium',
                            'bandit' => 'no',
                            'sibling' => 0
                        ])?>

                    <?=$this->element('playerWeek', [
                            'number' => 3,
                            'rating' => 'low',
                            'bandit' => 'no',
                            'sibling' => 1
                        ])?>


                </ol>

            </div>

        </section>

    </div>

    <div class="col">

        <section class="block is--animating">

            <header class="block__header">
                <h1 class="h4">Disputes</h1>
            </header>

            <ol class="stream is--disputes">

                <?= $this->element('finder'); ?>

            </ol>

        </section>

        <section class="block is--animating">

            <header class="block__header">
                <h1 class="h4">Notifications</h1>
            </header>

            <ol class="stream is--notifications">
            </ol>

        </section>

    </div>

</article>

<article class="display g2">

    <div class="col">

        <section class="block has--contents">

            <header class="block__header">
                <h1 class="h4__small">Leaderboards</h1>
            </header>

            <div class="block__contents gflex from--desktop">

                <div class="col">

                    <h2 class="h4 stream__title"><a href="#">This Week</a></h2>

                    <ol class="stream is--players">

                        <?=$this->element('playerWeek', [
                                'number' => 1,
                                'rating' => 'high',
                                'bandit' => 'no',
                                'sibling' => 1
                            ])?>

                        <?=$this->element('playerWeek', [
                                'number' => 2,
                                'rating' => 'medium',
                                'bandit' => 'no',
                                'sibling' => 0
                            ])?>

                        <?=$this->element('playerWeek', [
                                'number' => 3,
                                'rating' => 'low',
                                'bandit' => 'no',
                                'sibling' => 1
                            ])?>


                    </ol>

                </div>

                <div class="col">

                    <h2 class="h4 stream__title"><a href="#">All Time</a></h2>

                    <ol class="stream is--players">

                        <?=$this->element('player', [
                                'number' => 1,
                                'rating' => 'high',
                                'bandit' => 'yes',
                                'sibling' => 2
                            ])?>

                        <?=$this->element('player', [
                                'number' => 2,
                                'rating' => 'medium',
                                'bandit' => 'no',
                                'sibling' => 1
                            ])?>

                        <?=$this->element('player', [
                                'number' => 3,
                                'rating' => 'low',
                                'bandit' => 'no',
                                'sibling' => 0
                            ])?>

                    </ol>

                </div>

            </div>

        </section>

    </div>

</article>