var elixir = require('laravel-elixir');
require('laravel-elixir-vueify');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir.config.js.browserify.watchify.options.poll = true;
elixir(function(mix) {
    mix.sass('app.scss', null, {includePaths: 'node_modules/bootstrap-sass/assets/stylesheets/'});
});

elixir(function(mix) {
	    mix.browserify('main.js', 'public/js/app.js');
});