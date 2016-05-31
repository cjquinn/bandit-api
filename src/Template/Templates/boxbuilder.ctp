<header class="header g2">

    <div class="col">

        <h1 class="header__title h1">Build a Box League</h1>

        <ol class="tabs">
            <li class="tabs__tab is--active"><button class="tabs__tab__button">Build</li>
            <li class="tabs__tab"><button class="tabs__tab__button">My Matches</li>
            <li class="tabs__tab"><button class="tabs__tab__button">Box 1</li>
            <li class="tabs__tab"><button class="tabs__tab__button">Box 2</li>
            <li class="tabs__tab"><button class="tabs__tab__button">Box 3</li>
            <li class="tabs__tab"><button class="tabs__tab__button">Box 4</li>
            <li class="tabs__tab"><button class="tabs__tab__button">Box 5</li>
        </ol>

    </div>

</header>

<article class="display g2">

    <div class="col">

        <section class="block">

            <header class="block__header justify-space-between-baseline">
                <h1 class="h4">Players</h1> <small class="h4__small">26 players available</small>
            </header>

            <ol class="stream is--players">

                <li class="finder__display">

                    <figure class="finder__player-photo">

                        <?= $this->Svg->useit('player', 'finder__player-photo__icon') ?>

                        <div class="player-photo__proportion clip-hexagon">


                        </div>

                    </figure>

                    <input class="finder__display__input" type="text" required placeholder="Search Players&hellip;"/>
                </li>

                <?php

                    for ($k = 0; $k < 6; $k++) {

                        echo $this->element('playerAvailable', [
                                'number' => 3,
                                'rating' => 'high',
                                'bandit' => 'no',
                                'sibling' => 0
                            ]);

                    }

                    for ($k = 0; $k < 6; $k++) {

                        echo $this->element('playerAvailable', [
                                'number' => 3,
                                'rating' => 'medium',
                                'bandit' => 'no',
                                'sibling' => 0
                            ]);

                    }

                    for ($k = 0; $k < 9; $k++) {

                        echo $this->element('playerAvailable', [
                                'number' => 3,
                                'rating' => 'low',
                                'bandit' => 'no',
                                'sibling' => 0
                            ]);

                    }
                ?>

            </ol>

        </section>

    </div>

    <div class="col">

        <section class="block">

            <header class="block__header justify-space-between-baseline">
                <h1 class="h4">Box 1</h1> <span class="h4__small">3 spaces left</span>
            </header>

            <ol class="stream is--players">

                <?php

                    echo $this->element('playerAvailable', [
                                'number' => 3,
                                'rating' => 'high',
                                'bandit' => 'yes',
                                'sibling' => 0
                            ]);

                    for ($k = 0; $k < 3; $k++) {

                        echo $this->element('playerAvailable', [
                                'number' => 3,
                                'rating' => 'high',
                                'bandit' => 'no',
                                'sibling' => 0
                            ]);

                    }


                ?>

                <li class="block__slab is--empty">

                    <p class="">Drag &amp; drop players to populate box.</p>

                </li>

            </ol>

        </section>


        <section class="block">

            <header class="block__header justify-space-between-baseline">
                <h1 class="h4">Box 2</h1> <small class="h4__small">5 spaces left</small>
            </header>

            <ol class="stream is--players">

                <?php
                    
                    for ($k = 0; $k < 2; $k++) {

                        echo $this->element('playerAvailable', [
                                'number' => 3,
                                'rating' => 'medium',
                                'bandit' => 'no',
                                'sibling' => 0
                            ]);

                    }


                ?>

                <li class="block__slab is--empty">

                    <p class="">Drag &amp; drop players to populate box.</p>

                </li>

            </ol>

        </section>


        <section class="block">

            <button class="button is--full is--xl">
                <div class="button__icon">
                    <figure class="button__icon__clip clip-hexagon"><?= $this->Svg->useit('plus', 'icon-plus button__icon__svg') ?></figure>
                </div>Add Box
            </button>

        </section>


    </div>

</article>