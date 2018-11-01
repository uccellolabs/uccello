const mix = require('laravel-mix');
const path = require('path');

const uitypePath = path.resolve('./resources/assets/js/uitype-selector.js')

var autoload = {
    jquery: [ '$', 'jQuery', 'jquery']
}
autoload[uitypePath] = ['UccelloUitypeSelector']

mix.autoload(autoload);

mix.setPublicPath('public');

mix.js('./resources/assets/js/app.js', 'public/js')
   .sass('./resources/assets/sass/app.scss', 'public/css');

mix.js('./resources/assets/js/core/autoloader.js', 'public/js');

mix.copy('./resources/assets/images', 'public/images');

mix.extract([
    'lodash', 'jquery', 'bootstrap',
    'fastclick', 'adminbsb-materialdesign',
    'vue', 'axios', 'node-waves', 'popper.js', 'moment'
], 'public/js/vendor.js');

mix.version();

// Copy all compiled files into main project (auto publishing)
mix.copyDirectory('public', '../../../public/vendor/uccello/uccello');