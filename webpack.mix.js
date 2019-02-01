const mix = require('laravel-mix');
const path = require('path');
const uitypePath = path.resolve('./resources/assets/js/uitype-selector.js')

var autoload = {
    jquery: [ '$', 'jQuery', 'jquery']
}
autoload[uitypePath] = ['UccelloUitypeSelector']

mix.autoload(autoload)
    .setPublicPath('public')

    .js('./resources/assets/js/app.js', 'public/js')
    .sass('./resources/assets/sass/app.scss', 'public/css')

    .js('./resources/assets/js/core/autoloader.js', 'public/js')
    .js('./resources/assets/js/settings/autoloader.js', 'public/js/settings')

    .extract([
        'lodash', 'jquery', 'bootstrap',
        'fastclick', 'adminbsb-materialdesign',
        'vue', 'axios', 'node-waves', 'popper.js', 'moment'
    ], 'public/js/vendor.js')

    .version()

    // Copy all compiled files into main project (auto publishing)
    .copy('./resources/assets/images', 'public/images')
    .copyDirectory('public', '../../../public/vendor/uccello/uccello')
    .copyDirectory('public/fonts/vendor', '../../../public/fonts/vendor')
    .copyDirectory('public/images/vendor', '../../../public/images/vendor')