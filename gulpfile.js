var elixir = require('laravel-elixir');
var fs = require('fs');

/**
 * Config
 */
var config = JSON.parse(fs.readFileSync('./.gulprc', 'utf8'));

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
    // Copy files
    mix.copy(
        elixir.config.assetsPath + '/img',
        elixir.config.publicPath + '/img'
    );

    // Scripts
    mix.webpack('main.js');

    // Styles
    mix.sass('style.scss');

    // browserSync
    mix.browserSync();
});
