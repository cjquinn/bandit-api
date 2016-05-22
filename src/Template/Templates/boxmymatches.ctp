<header class="header g2">

    <div class="col">

        <h1 class="header__title h1">Season <small class="h1__small">Ends June 16th</small></h1>

        <ol class="tabs">
            <li class="tabs__tab"><button class="tabs__tab__button">Build</li>
            <li class="tabs__tab"><button class="tabs__tab__button">My Matches</li>
            <li class="tabs__tab is--active"><button class="tabs__tab__button">Box 1</li>
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

            <header class="block__header">
                <h1 class="h4">Your Opponents</h1>
            </header>

            <ol class="stream is--matches">

                <?php

                    for ($k = 0; $k < 3; $k++) {

                        echo $this->element('playerBoxOpponent');

                    }


                    for ($k = 0; $k < 3; $k++) {

                        echo $this->element('playerBoxOpponentWaiting');

                    }

                    echo $this->element('playerBoxOpponentReceived');

                ?>

            </ol>

        </section>

    </div>

</article>