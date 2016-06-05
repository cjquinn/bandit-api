<aside class="prompt" data-prompt-id="add-wins">

    <button class="prompt__close">
        <?= $this->Svg->useit('cross', 'prompt__close__svg close'); ?>
    </button>

    <section class="prompt__message block has--contents">

        <header class="block__header justify-space-between-baseline">
            <h1 class="h4">Add Wins</h1> <span class="h4__small">Saturday 8th June</span>
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