let mix = require('laravel-mix');

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.js('resources/assets/js/list.js', 'public/js');

mix.autoload({
    jquery: [ '$', 'jQuery', 'jquery'],
});

mix.extract([
    'lodash', 'jquery', 'bootstrap',
    'fastclick', 'jquery-slimscroll', 'adminbsb-materialdesign',
    'vue', 'axios', 'node-waves'
], 'public/js/vendor.js');

mix.version();

mix.setPublicPath('public');

// Copy all compiled files into main project
mix.copyDirectory('public', '../../../public');