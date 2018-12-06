<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
	'Dashboard\Controllers' 	=> __DIR__ . '/../controllers/dashboard/',
	'Common\Controllers' 		=> __DIR__ . '/../controllers/common/',
    'Aiden\Models' 				=> __DIR__ . '/../models/',
    'Aiden\Controllers' 		=> __DIR__ . '/../controllers/dashboard',
    'Aiden\Controllers\Admin' 	=> __DIR__ . '/../controllers/dashboard/admin/',
    'Aiden\Controllers\Users' 	=> __DIR__ . '/../controllers/dashboard/users/',
    'Aiden\Forms' 				=> __DIR__ . '/../forms/',
    'Aiden\Classes' 			=> __DIR__ . '/../classes/',
]);
$loader->register();

