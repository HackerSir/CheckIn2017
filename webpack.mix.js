const {mix} = require('laravel-mix');

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
let buildJsPath = 'public/build-js';
let buildCssPath = 'public/build-css';

mix.js('resources/js/vue.js', buildJsPath)
    .js('resources/js/checkin.js', buildJsPath)
    .js('resources/js/broadcast-test.js', buildJsPath)
    .version();

mix.copy('node_modules/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css', buildCssPath)
    .version();
