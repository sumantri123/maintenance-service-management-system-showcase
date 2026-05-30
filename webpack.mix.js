const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.styles([                		
		'public/backend/css/bootstrap.min.css',	     
		'public/backend/css/app.css',	     
		'public/backend/css/icons.css',	     	   	     	  
		'public/backend/plugins/jquery-ui-1.12.1/jquery-ui.min.css',	     		
		'public/backend/plugins/jquery-ui-1.12.1/jquery-ui.css',	     
		'public/backend/plugins/select2/css/select2.min.css',	     
		'public/backend/plugins/select2/css/select2-bootstrap4.css',	     
		'public/backend/plugins/Drag-And-Drop/dist/imageuploadify.min.css',	     
		'public/backend/plugins/sweetalert2/dist/sweetalert2.min.css',	     
   ], 'public/backend/mix/frontend.css');
   
mix.scripts([
		'public/backend/js/bootstrap.bundle.min.js',		
		'public/backend/js/jquery.min.js',
		'public/backend/plugins/Drag-And-Drop/dist/imageuploadify.min.js',
		'public/backend/plugins/sweetalert2/dist/sweetalert2.js',
		'public/backend/js/app.js',
		'public/backend/plugins/select2/js/select2.min.js',
		'public/backend/plugins/notifications/js/lobibox.min.js',
		'public/backend/plugins/notifications/js/notifications.min.js',
		'public/additional/js/notification-custom-script.js',
		'public/backend/plugins/jquery-ui-1.12.1/jquery-ui.min.js',
		'public/backend/plugins/jquery-ui-1.12.1/jquery-ui.js',		
   ], 'public/backend/mix/frontend.js');   