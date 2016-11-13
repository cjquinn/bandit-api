<section class="block has--contents has--multiple">

    <header class="block__header">
        <h1 class="h4__small caps"><a href="/templates/leaderboards">Leaderboards</a></h1>
    </header>

    <div class="block__contents gflex from--desktop">

        <div class="col">

            <h2 class="h4 stream__title">This Week</h2>

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
                        'sibling' => 0,
                        'name' => 'You',
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

            <h2 class="h4 stream__title">All Time</h2>

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
                        'name' => 'You',
                        'sibling' => 0,
                    ])?>

            </ol>

        </div>

    </div>

</section>