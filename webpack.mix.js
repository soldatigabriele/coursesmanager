let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.css('node_modules/gijgo/combined/css/gijgo.min.css', 'public/css');

mix.js('node_modules/gijgo/combined/js/gijgo.min.js', 'public/js');
mix.js('resources/assets/js/app.js', 'public/js');
