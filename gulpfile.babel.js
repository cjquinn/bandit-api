import elixir from 'laravel-elixir';
import fs from 'fs';

/**
 * Config
 */
const config = JSON.parse(fs.readFileSync('./.gulprc', 'utf8'));

elixir.config.assetsPath = 'assets';
elixir.config.notifications = config.notifications;
elixir.config.publicPath = 'webroot';
elixir.config.viewPath = 'src/Template';

elixir.config.css.sass.folder = 'scss';
elixir.config.css.sass.search = '/**/*.scss';

elixir.config.browserSync.proxy = config.browserSync.proxy;

/**
 * Mix!
 */
elixir(function(mix) {
    mix.webpack('main.js');

    // browserSync
    mix.browserSync();
});
