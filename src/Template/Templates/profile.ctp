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

    <div class="col">



    </div>

</article>


<article class="display g2">

    <div class="col">

        <section class="level block has--contents">

            <header class="block__header">
                <h1 class="h4__small">Level</h1>
                <h2 class="h4">Ninja</h2>
            </header>

            <div class="block__contents">

                <div class="block__base level__base g5">

                    <div class="level__info is--49 col--1">
                        <?= $this->Svg->useit('brand-mask', 'mask is--level49 level__mask-icon'); ?>
                        <h3 class="h5 level__name">Ninja</h3>
                        <p class="level__minimum">9000+</p>
                    </div>

                    <div class="col--2">

                        <figure class="level__progress">
                            <div class="level__progress-complete is--level49" style="width: 67%;">
                            </div>
                        </figure>
                        
                    </div>

                    <div class="level__info is--50 col--3">
                        <?= $this->Svg->useit('brand-mask', 'mask is--level50 level__mask-icon'); ?>
                        <h3 class="h5 level__name">Warrior</h3>
                        <p class="level__minimum">10000+</p>
                    </div>
                </div>

            </div>

        </section>

    </div>

</article>




<article class="display g2">

    <div class="col">

        <section class="block has--contents">

            <header class="block__header">
                <h1 class="h4__small"><a href="/templates/leaderboards/">Leaderboards</a></h1>
            </header>

            <div class="block__contents gflex from--desktop">

                <div class="col">

                    <h2 class="h4 stream__title"><a href="#">This Week</a></h2>

                    <ol class="stream is--players is--snapshot">

                        <?=$this->element('playerWeek', [
                                'number' => 1,
                                'rating' => 'high',
                                'bandit' => 'yes',
                                'sibling' => 2
                            ])?>

                        <?=$this->element('playerWeek', [
                                'number' => 2,
                                'rating' => 'high',
                                'bandit' => 'no',
                                'sibling' => 1
                            ])?>

                        <?=$this->element('playerWeek', [
                                'number' => 3,
                                'rating' => 'medium',
                                'bandit' => 'no',
                                'sibling' => 0
                            ])?>

                        <?=$this->element('playerWeek', [
                                'number' => 4,
                                'rating' => 'low',
                                'bandit' => 'no',
                                'sibling' => 1
                            ])?>

                        <?=$this->element('playerWeek', [
                                'number' => 5,
                                'rating' => 'low',
                                'bandit' => 'no',
                                'sibling' => 2
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