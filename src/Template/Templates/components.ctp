<style>
    [aria-role="hidden"] {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        border: 0;
    }

    .result {
        display: flex;
    }

    .result__score,
    .result__player {
        flex: 1;
    }

    .result__player--1 {
        order: 1;
    }

    .result__score {
        order: 2;
    }

    .result__player--2 {
        order: 3;
    }


    .plus:before {
        content: '+';
    }

    .minus:before {
        content: '-';
    }

    .result__player {
        display: flex;
    }

    .result__score {
        text-align: center;
    }

</style>

<dl class="result">

    <?php

    /* Not specific to this result - this would exist on the stream

    <dt>Date:</dt>
    <dd>Tuesday 13th January 2015</dd>

    */
    ?>

    <div class="result__score">
        <dt aria-role="hidden">Match Score:</dt>
        <dd><span aria-role="hidden">Alan Baker</span> 2 &ndash; <span aria-role="hidden">Brett Southland</span> 1</dd>
    </div>

    <div class="result__player result__player--1">

        <div class="player-photo" role="presentation">

            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" class="player-photo__image" />

            </div>

        </div>

        <dt aria-role="hidden">Winner:</dt>
        <dd>Alan Baker
            <dl>
                <dt aria-role="hidden">Rating:</dt> <dd>1650.</dd>
                <dt aria-role="hidden">Level:</dt> <dd aria-role="hidden">Ninja.</dd>
                <dt aria-role="hidden">Points gained:</dt> <dd class="plus">185.</dd>
            </dl>
        </dd>
    </div>

    <div class="result__player result__player--2">

        <div class="player-photo" role="presentation">

            <div class="player-photo__proportion clip-hexagon">

                <div class="player-photo__level is--level<?=rand(47, 55)?>"></div>
                <img src="http://userphoto.russellbishop.co.uk/get.php?id=<?=rand(0, 40)?>" class="player-photo__image" />

            </div>

        </div>

        <dt aria-role="hidden">Loser:</dt>
        <dd>Brett Southland
            <dl>
                <dt aria-role="hidden">Rating:</dt> <dd>1495</dd>
                <dt aria-role="hidden">Level:</dt> <dd aria-role="hidden">Knight</dd>
                <dt aria-role="hidden">Points lost:</dt> <dd class="minus">15</dd>
            </dl>
        </dd>
    </div>

</dl>
