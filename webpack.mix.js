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

mix.js('resources/assets/js/app.js', 'public/js')
    .extract(['axios','bootstrap', 'jquery', 'lodash', 'popper.js','vue'])
    .sass('resources/assets/sass/app.scss', 'public/css')
    ;

if (mix.config.production) {
    mix
        // .minify()
        .version()
    ;
} else {
    mix
        .webpackConfig({ devtool: "source-map" })
        .sourceMaps()
    ;
}