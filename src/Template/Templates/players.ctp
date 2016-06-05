<header class="header g2">

    <div class="col">

        <h1 class="header__title h1">Players <small class="h1__small">36 members of Britannia Squash Club</small></h1>

        <?php /*

        <ol class="tabs">
            <li class="tabs__tab"><button class="tabs__tab__button">A-Z</li>
            <li class="tabs__tab"><button class="tabs__tab__button">My Matches</li>
            <li class="tabs__tab is--active"><button class="tabs__tab__button">Box 1</li>
        </ol>

        */ ?>

    </div>

</header>

<article class="display g2">

    <div class="col">

        <section class="block">

            <header class="block__header justify-space-between-baseline">
                <h1 class="h4">All Players</h1>
                <select>
                    <option>A-Z First Name</option>
                    <option>Most Games</option>
                    <option>Least Games</option>
                    <option>Recently Joined</option>
                </select>
            </header>

            <ol class="stream matches">

                    <?=$this->element('finder')?>

                <?php

                for ($k = 0; $k < 3; $k++) { 


                    echo $this->element('playerContact', [
                        'number' => 2,
                        'rating' => 'medium'
                    ]);

                }

                ?>

            </ol>

        </section>

    </div>

    <div class="col">

        <section class="block">

            <header class="block__header">
                <h1 class="h4">Contacts</h1>
            </header>

            <ol class="stream is--players">



            </ol>

        </section>


    </div>

</article>