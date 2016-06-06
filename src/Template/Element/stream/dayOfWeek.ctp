<section class="block">

        <header class="block__header">
            <h1 class="h4">Monday <small class="h3__small">6th June</small></h1>
        </header>

        <ol class="stream matches">

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

                <li class="block__footer__meta__item">

                    <?= $this->Svg->useit('icon-matches', 'block__footer__icon'); ?>

                    5 Matches

                </li>

                <li class="block__footer__meta__item">

                    <?= $this->Svg->useit('icon-players', 'block__footer__icon'); ?>

                    8 Players

                </li>


            </ul>

        </footer>

    </section>