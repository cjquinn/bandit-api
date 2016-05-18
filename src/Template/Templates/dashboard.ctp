<header class="header g2">

    <div class="col">

        <h1 class="header__title h1">Dashboard</h1>

        <ol class="tabs">
            <li class="tabs__tab is--active"><button class="tabs__tab__button">Activity</li>
            <li class="tabs__tab"><button class="tabs__tab__button"><span class="tabs__tab__button__count">5</span> Notifications</li>
            <li class="tabs__tab"><button class="tabs__tab__button">My Account</li>
            <li class="tabs__tab" style="float:right;"><button class="tabs__tab__button">Disputes</li>
        </ol>

    </div>

</header>

<article class="display g2">

    <div class="col">

        <section class="block is--animating">

            <header class="block__header">
                <h1 class="h4">Matches</h1>
            </header>

            <ol class="stream is--matches">

                <?php

                    $stream = 1;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('matchBandit');

                    }

                    $stream = 3;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('match');

                    }

                    $stream = 1;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('matchLoss');

                    }
                ?>

            </ol>

            <footer class="block__footer">

                <ul class="block__footer__meta">


                </ul>

            </footer>

        </section>


        

    </div>

    <div class="col">

       <section class="block is--animating">

            <header class="block__header">
                <h1 class="h4">Box 1 <small class="h4__small">3 weeks left</small></h1>
            </header>

            <ol class="stream is--matches">

                <?php

                    $stream = 1;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('matchBandit');

                    }

                    $stream = 2;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('match');

                    }

                    $stream = 1;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('matchDraw');

                    }

                    $stream = 1;

                    for ($k = 0; $k < $stream; $k++) {

                        echo $this->element('matchLoss');

                    }
                ?>

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