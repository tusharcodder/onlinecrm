const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.js(['public/assets/lib/jquery/jquery.min.js',
		'public/assets/lib/bootstrap/js/bootstrap.bundle.min.js',
		'public/assets/lib/feather-icons/feather.min.js',
		'public/assets/lib/perfect-scrollbar/perfect-scrollbar.min.js',
		'public/assets/lib/jquery.flot/jquery.flot.js',
		'public/assets/lib/jquery.flot/jquery.flot.stack.js',
		'public/assets/lib/jquery.flot/jquery.flot.resize.js',
		'public/assets/lib/chart.js/Chart.bundle.min.js',
		'public/assets/lib/jqvmap/jquery.vmap.min.js',
		'public/assets/lib/jqvmap/maps/jquery.vmap.usa.js',
		'public/assets/assets/js/dashforge.js',
		'public/assets/assets/js/dashforge.sampledata.js',
		'public/assets/assets/js/dashboard-one.js',    
		'public/assets/lib/js-cookie/js.cookie.js',
		'public/assets/assets/js/dashforge.settings.js'],'public/js/all.js')