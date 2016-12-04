<header class="header g3">

    <div class="col">

        <h1 class="header__title h1">Matches</h1>

    </div>

    <div class="col">

        <h1 class="header__title h1">Leaderboard</h1>

    </div>

    <nav class="form__select header__select">
        <h1 class="h2">This Week</h1>
        
        <label>
            <select>
                <option disabled>This Week:</option>
                <option selected>Matches</option>
                <option>Leaderboard</option>
            </select>
            <?= $this->Svg->useit('rarr', 'rarr header__select__rarr') ?>
        </label>
    </nav>

</header>

<article class="display g2">

    <div class="col">

        <?php for ($k = 1; $k < 5; $k++) {

            echo $this->element('stream/dayOfWeek');

        } ?>

    </div>

    <div class="col">

        <section class="block">

            <header class="block__header">
                <h1 class="h4">This Week</h1>
            </header>

            <ol class="stream is--players">

                <?php

                for ($k = 1; $k < 7; $k++) {

                        echo $this->element('playerWeek', [
                                'number' => $k,
                                'rating' => 'high',
                                'bandit' => 'no',
                                'sibling' => 0
                        ]);

                }

                ?>

            </ol>

            <footer class="block__footer">

            <ul class="block__footer__meta">

                <li class="block__footer__meta__item">

                    <?= $this->Svg->useit('icon-matches', 'block__footer__icon'); ?>

                    45 Matches

                </li>

                <li class="block__footer__meta__item">

                    <?= $this->Svg->useit('icon-players', 'block__footer__icon'); ?>

                    18 Players

                </li>


            </ul>

        </footer>

        </section>


    </div>

</article>