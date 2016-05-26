<aside class="prompt is--open" data-prompt-id="add-wins">

    <button class="prompt__close">
        <?= $this->Svg->useit('cross', 'prompt__close__svg'); ?>
    </button>


    <section class="prompt__message block has--contents">

        <header class="block__header">
            <h1 class="h4">Add Wins <span class="h4__small">Saturday 8th June</span></h1>                
        </header>

        <div class="block__contents form">

            <fieldset>
                <label class="form__label">Choose your opponent:</label>
                <?=$this->element('finder')?>
            </fieldset>

            <fieldset class="form__stepper">
                <label class="form__label">How many wins?:</label>

                <div class="relative">
                    <button class="pick is--up">
                        <?= $this->Svg->useit('rarr', 'pick__icon is--up') ?>
                    </button>

                    <button class="pick is--down">
                        <?= $this->Svg->useit('rarr', 'pick__icon is--down') ?>
                    </button>

                    <input type="number" value="1" min="1" max="10" class="form__input" />
                </div>
            </fieldset>

            <p><button>+ More Opponents</button></p>

            <button class="button is--full is--l">
                <div class="button__icon">
                    <figure class="button__icon__clip"><?= $this->Svg->useit('tick', 'icon-tick button__icon__svg') ?></figure>
                </div>Submit Wins
            </button>

        </div>

    </section>


</aside>

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

            <ol class="stream matches">

                <?php

                    for ($k = 0; $k < 3; $k++) {

                        echo $this->element('playerBoxOpponent');

                    }


                    for ($k = 0; $k < 3; $k++) {

                        echo $this->element('playerBoxOpponentWaiting');

                    }

                    echo $this->element('playerBoxOpponentReceived');

                    echo $this->element('playerBoxOpponentAccepted');

                ?>

            </ol>

        </section>

    </div>

</article>