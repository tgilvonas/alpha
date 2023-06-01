let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'js')
    .vue({ version: 3 })
    .sass('resources/sass/app.scss', 'css');
