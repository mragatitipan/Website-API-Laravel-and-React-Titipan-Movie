const mix = require('laravel-mix');

/*
|--------------------------------------------------------------------------
| Mix Asset Management
|--------------------------------------------------------------------------
*/

mix.js('resources/js/app.js', 'public/js')
    .react() // Enable React support
    .sass('resources/sass/app.scss', 'public/css')
    .version(); // Cache busting

// Source maps untuk development
if (!mix.inProduction()) {
    mix.sourceMaps();
}
