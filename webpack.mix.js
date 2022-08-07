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

 // For Theme
mix.sass('resources/assets/sass/app.scss', 'public/css')
   .js([
      'resources/assets/js/app.js'
   ], 'public/js/app.js');

// For Admin
mix.styles([
       'resources/assets/css/bootstrap.min.css',
       'resources/assets/css/font-awesome.min.css',
       'resources/assets/css/fontawesome-iconpicker.css',
       'resources/assets/css/bootstrap_datepicker.css',
       'resources/assets/css/bootstrap_timepicker.css',
       'resources/assets/css/select2.css',
       'resources/assets/css/datatables.min.css',
       'resources/assets/css/ionicons.css',
       'resources/assets/css/AdminLTE.css',
       'resources/assets/css/bootstrap-switch.css',
       'resources/assets/css/skin-blue.css',
       'resources/assets/css/mdb.min.css',
       'resources/assets/css/custom.css'
   ],  'public/css/admin.css')
   .js([
      'resources/assets/js/jquery.min.js',    
      'resources/assets/js/bootstrap.js',
      'resources/assets/js/select2.js',
      'resources/assets/js/datepicker.js',
      'resources/assets/js/timepicker.js',
      'resources/assets/js/adminlte.js',
      'resources/assets/js/demo.js',
      'resources/assets/js/fontawesome-iconpicker.min.js',
      'resources/assets/js/bootstrap-switch.min.js',
      'resources/assets/js/buttons.js',
      'resources/assets/js/chart.js',
      'resources/assets/js/dropdown.js',
      'resources/assets/js/forms-basic.js',
      'resources/assets/js/global.js',
      'resources/assets/js/jquery-easing.js',
      'resources/assets/js/waves-effect.js',
      'resources/assets/js/wow.js',
      'resources/assets/js/mdb.min.js',
      'resources/assets/js/custom.js'
   ], 'public/js/admin.js');
