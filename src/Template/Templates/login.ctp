<?php
    $this->viewBuilder()->layout('respondent');
?>

</article>
</div>
</div>

<?php

    /* lol, end previous template markup. I'm terrible. */

?>

<article class="login">

    <header class="login__brand">

        <a href="http://banditplay.com">
            <?= $this->Svg->useit('brand-logo', 'login__logo'); ?>
        </a>

        <h1><a href="http://banditplay.com">Bandit</a></h1>
        <h2>Play, win, bandit.</h2>


    </header>

    <fieldset>
        
        <input type="text" name="email" required />
        <label>Email:</label>

    </fieldset>

    <fieldset>
        
        <input type="password" name="password" required />
        <label>Password:</label>

    </fieldset>

    <fieldset>

        <button class="button">Let's do this!</button>

    </fieldset>

    <footer class="login__links gleftright">

        <a href="">Sign yourself up</a>

        <a href="">I think I forgot my password</a>

    </footer>

</article>

<style>
.app { display: none; }
</style>