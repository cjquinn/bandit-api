<li class="result">

    <div class="result__score">
        <dt aria-role="hidden">Match Score:</dt>
        <dd><span aria-role="hidden">Alan Baker</span> 2 <span class="result__score__dash">&ndash;</span> <span aria-role="hidden">Brett Southland</span> 1</dd>
    </div>

    <?php for ($player=1; $player <= 2; $player++) : ?>

    <?php
        $level = rand(47, 55);
        $verdict = ($player==1 ? 'Winner' : 'Loser');
        $difference = ($verdict=='Winner' ? 'gained' : 'lost');
    ?>

    <div class="result__player result__player--<?=strtolower($verdict)?> result__player--<?=$player;?> player">

        <div class="player__photo clip-hexagon" role="presentation" style="background-image: url('/img/temp/users/a.jpg');">

            <div class="level is--level<?=$level?>"></div>

        </div>

        <dt aria-role="hidden"><?=$verdict?>:</dt>

        <dd class="player__info"><span class="player__name" title="Anthony Beckenhamberg">Anthony</span>
            <dl>
                <div class="player__rating">
                    <dt aria-role="hidden">Rating:</dt> <dd class="player__rating">1650</dd>
                </div>
                <dt aria-role="hidden">Level:</dt> <dd aria-role="hidden">Ninja</dd>
                <dt aria-role="hidden">Points <?=$difference?>:</dt> <dd class="result__points u-<?=$difference?>">185</dd>
            </dl>
        </dd>
    </div>

    <?php endfor; ?>

</li>