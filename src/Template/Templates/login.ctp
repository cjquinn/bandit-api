<article class="login">

    <header class="login__brand">

        <a href="http://banditplay.com" class="login__brand__logo">
            <?= $this->Svg->useit('brand-logo', 'login__brand__logo__svg'); ?>
        </a>

        <h1 class="login__brand__name"><a href="http://banditplay.com">Bandit</a></h1>
        <h2 class="login__brand__descriptor">Play, win, bandit.</h2>

    </header>

    <fieldset class="login__fieldset">
        
        <input class="login__input" type="email" name="email" placeholder="&nbsp;" required />
        <label class="login__label" for="email">Email:</label>

    </fieldset>

    <fieldset class="login__fieldset">
        
        <input class="login__input" type="password" name="password" placeholder="&nbsp;" required />
        <label class="login__label" for="password">Password:</label>

    </fieldset>

    <fieldset class="login__fieldset">

        <button class="button is--full is--l">Let's do this!</button>

    </fieldset>

    <footer class="login__links gleftright">

        <a href="#">New players</a>

        <a href="#">Forgot my password</a>

    </footer>

</article>

<?php /* 
<article class="explain content paragraph-spacing">

    <h3 class="h4">We're putting an end to boring games leaderboards.</h3>

    <p>We won't speak for you, but the Bandit team are bored out of our minds by leagues and tournaments looking like newspaper crosswords.</p>

    <p>That's why we built a club system as exciting as the sports you play.</p>

</article>*/ ?>
