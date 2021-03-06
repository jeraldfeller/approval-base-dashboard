<?php

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 */
define('ENVIRONMENT', 'production');

error_reporting(E_ALL);

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 */
switch (ENVIRONMENT) {

    case 'staging':
    case 'production':
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        define('BASE_URI', 'http://dev.approval-base-new.com/');
        define('ADMIN_EMAIL', 'jeraldfeller@gmail.com');
        break;

    case 'development':
        define('BASE_URI', 'http://approvalbase.com/dev/');
        define('ADMIN_EMAIL', 'jeraldfeller@gmail.com');
        break;
    case 'local':
        define('BASE_URI', 'http://dev.approval-base.com/');
        define('ADMIN_EMAIL', 'jeraldfeller@gmail.com');
        break;
    default:
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        define('BASE_URI', 'http://dev.approval-base.com/');
        define('ADMIN_EMAIL', 'jeraldfeller@gmail.com');
        break;
}

/*
 *---------------------------------------------------------------
 * CONFIG
 *---------------------------------------------------------------
 */
$config = require __DIR__ . "/../app/config/" . ENVIRONMENT . "/config.php";

/**
 * Include loader
 */
require __DIR__ . '/../app/config/loader.php';

/**
 * Include services
 */
require __DIR__ . '/../app/config/services.php';

/**
 * PHP mailer auto loader
 */
//Load Composer's autoloader
require __DIR__.'/../vendor/autoload.php';


/**
 * Handle the request
 */
$application = new \Phalcon\Mvc\Application();
$application->setDI($di);
echo $application->handle()->getContent(); /*
} catch (Phalcon\Exception $e) {
    echo $e->getMessage();
} catch (PDOException $e) {
    echo $e->getMessage();
} */
