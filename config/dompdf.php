<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DomPDF Public Path Override
    |--------------------------------------------------------------------------
    |
    | Laravel DomPDF sometimes fails to detect the correct public path if your
    | Laravel installation does not use the default "public" folder (e.g. you
    | use "public_html" in shared hosting). Setting this value fixes that.
    |
    */

    'public_path' => '/home/u860834787/domains/artautamaadijaya.com/public_html',
	'options' => [
        'isRemoteEnabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Other default settings (optional)
    |--------------------------------------------------------------------------
    |
    | These are the defaults DomPDF uses, you can leave them as-is.
    |
    */
    'show_warnings' => false,
    'orientation' => 'portrait',
    'defines' => [
        'font_dir' => storage_path('fonts/'),
        'font_cache' => storage_path('fonts/'),
        'temp_dir' => storage_path('app/temp'),
        'chroot' => realpath(base_path()),
    ],
];
