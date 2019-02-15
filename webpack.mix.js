const mix = require('laravel-mix')

mix.js('./resources/assets/js/app.js', 'public/js')
   .sass('./resources/assets/sass/materialize.scss', 'public/css')
   .sass('./resources/assets/sass/app.scss', 'public/css')
   .version()

   // Copy all compiled files into main project (auto publishing)
   .copy('./resources/assets/images', 'public/images')
   .copyDirectory('public', '../../../public/vendor/uccello/uccello')
   .copyDirectory('public/fonts/vendor', '../../../public/fonts/vendor')
   .copyDirectory('public/images/vendor', '../../../public/images/vendor')