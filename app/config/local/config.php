<?php

return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'name' => 'approval_db',
    ],
    'baseUri' => 'http://dev.approval-base.com/',
    'dev' => true,
    'directories' => [
        'controllersDir' => __DIR__ . '/../../../app/controllers/',
        'commonControllersDir' => __DIR__ . '/../../../app/controllers/common',
        'dashboardControllersDir' => __DIR__ . '/../../../app/controllers/dashboard',
        'modelsDir' => __DIR__ . '/../../../app/models/',
        'viewsDir' => __DIR__ . '/../../../app/views/',
        'viewsDirDashboard' => __DIR__ . '/../../../app/views/dashboard/',
        'viewsDirCommon' => __DIR__ . '/../../../app/views/common/',
        'compiledDir' => __DIR__ . '/../../../app/compiled/', // Compiled .VOLT templates
        'classesDir' => __DIR__ . '/../../../app/classes/', // Custom classes
        'cookiesDir' => __DIR__ . '/../../../app/cookies/',
        'logsDir' => __DIR__ . '/../../../app/logs/',
    ],
    'dashboardEntriesLimit' => 10,
    'leadsLimit' => 100,
    'topCouncilsLimit' => 3,
    'htmlEntities' => [
        'starred' => '&#9733;',
        'unstarred' => '&#9734;'
    ],

    'googleMapAPI' => 'AIzaSyBeoB0tbJH7cwyD4i8cD6BPKzBB6-M4rX4'
]);


