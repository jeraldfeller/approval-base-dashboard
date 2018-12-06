<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\Model\MetaData\Memory as MemoryMetaData;
use Phalcon\Mvc\Model\MetaData\Strategy\Annotations as StrategyAnnotations;
use Phalcon\Logger\Adapter\File as FileAdapter;

$di = new FactoryDefault();

$di->setShared('router', function() {

    $router = require __DIR__ . '/routes.php';
    return $router;
});
$di->set('config', function() use ($config) {
    return $config;
});
$di->set('url', function() use ($config) {

    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri($config->baseUri);
    return $url;
});
$di->set('view', function() use ($config) {

    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir($config->directories->viewsDir);

    $volt = function($view, $di) use ($config) {

        $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
        $volt->setOptions([
            'compileAlways' => $config->dev, // Development only
            'compiledPath' => $config->directories->compiledDir,
        ]);

        $compiler = $volt->getCompiler();
        $compiler->addFunction("ceil", "ceil");
        $compiler->addFunction("round", "round");

        return $volt;
    };

    $view->registerEngines(['.volt' => $volt]);
    return $view;
});
$di->set('db', function() use ($config) {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->name
    ));
});
$di->set('session', function() {

    $session = new \Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
});
$di->set('dispatcher', function () {

    $eventsManager = new \Phalcon\Events\Manager();
    $eventsManager->attach('dispatch:beforeDispatch', new \Aiden\Classes\SecurityPlugin());

    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});
$di->set('flashSession', function() {

    $flashSession = new FlashSession();
    $flashSession->setAutoescape(false);
    return $flashSession;
});
$di->set('modelsMetadata', function() {

    // Instantiate a metadata adapter
    $metadata = new MemoryMetaData();

    // Set a custom metadata database introspection
    $metadata->setStrategy(new StrategyAnnotations());

    return $metadata;
});
$di->set('logger', function() use ($config) {

    $logName = date("d-m-Y") . ".log";
    $logger = new FileAdapter($config->directories->logsDir . $logName);
    return $logger;
});
