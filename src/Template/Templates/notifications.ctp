<header class="header g3">

    <div class="col">

        <h1 class="header__title h1">Notifications</h1>

        <ol class="tabs header__tabs">
            <li class="tabs__tab"><button class="tabs__tab__button">Activity</li>
            <li class="tabs__tab is--active"><button class="tabs__tab__button">Notifications <span class="tabs__tab__count is--alert">5</span></li>
            <li class="tabs__tab"><button class="tabs__tab__button">Disputes <span class="tabs__tab__count">0</span></li>
        </ol>

    </div>

    <nav class="form__select header__select">
        <h1 class="h2">Dashboard</h1>
        
        <label>
            <select>
                <option disabled>Dashboard:</option>
                <option >Activity</option>
                <option selected>Notifications (5)</option>
                <option>Disputes (0)</option>
            </select>
            <?= $this->Svg->useit('rarr', 'rarr header__select__rarr') ?>
        </label>
    </nav>

</header>

<article class="display g2">

    <div class="col">

        <section class="block">

            <header class="block__header justify-space-between-baseline">
                <h1 class="h4">Games</h1>
                <small class="h4__small">5 new</small>
            </header>

            <ol class="stream notifications">

                <?php

                    for ($k = 0; $k < 2; $k++) {

                        echo $this->element('playerAdded', [
                                'number' => 1,
                                'rating' => 'high',
                                'bandit' => 'yes',
                                'sibling' => 0
                            ]);

                    }
                ?>

            </ol>

        </section>

        <section class="block">

            <header class="block__header justify-space-between-baseline">
                <h1 class="h4">Leaderboards</h1>
            </header>

            <ol class="stream notifications">

                <li class="notification block__slab">

                    <dl class="player__id">

                        <figure class="player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <div class="clip-hexagon__contents">
                                    <span style="position: absolute;"><?= $this->Svg->useit('rarr', 'finder__display__rarr') ?>2</span>
                                </div>

                            </div>            

                        </figure>

                        <header>

                            <dt class="player__id__name">You moved 2 spaces up This Week's Leaderboard!</dt>

                            <dd class="player__id__stats">
                                <span class="player__id__action">Added 4 games against you today.</span>
                            </dd>

                        </header>

                    </dl>

                </li>

                <li class="notification block__slab">

                    <dl class="player__id">

                        <figure class="player__player-photo player-photo">

                            <div class="player-photo__proportion clip-hexagon">

                                <span style="position: absolute;"><?= $this->Svg->useit('rarr', 'finder__display__rarr') ?>1</span>

                            </div>            

                        </figure>

                        <header>

                            <dt class="player__id__name">You moved 1 space up the All Time Leaderboard!</dt>

                            <dd class="player__id__stats">
                                <span class="player__id__action">Added 4 games against you today.</span>
                            </dd>

                        </header>

                    </dl>

                </li>

            </ol>

        </section>


    </div>

    <div class="col">

    </div>

</article>