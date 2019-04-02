const mix = require('laravel-mix')

mix.setPublicPath('public')

mix.extract([
        'lodash',
        'jquery',
        'bootstrap',
        'popper.js',
        'fastclick',
    ], 'public/js/vendor.js')

mix.js('./resources/assets/js/core/autoloader.js', 'public/js')
    .js('./resources/assets/js/settings/autoloader.js', 'public/js/settings')
    .js('./resources/assets/js/profile/autoloader.js', 'public/js/profile')

mix.js('./resources/assets/js/app.js', 'public/js')
    .sass('./resources/assets/sass/materialize.scss', 'public/css')
    .sass('./resources/assets/sass/app.scss', 'public/css')

    .version()

// Copy all compiled files into main project (auto publishing)
   .copy('./resources/assets/images', 'public/images')
   .copyDirectory('public', '../../../public/vendor/uccello/uccello')
//    .copyDirectory('public/fonts/vendor', '../../../public/fonts/vendor')
//    .copyDirectory('public/images/vendor', '../../../public/images/vendor')