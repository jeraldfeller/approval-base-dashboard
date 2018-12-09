<?php

// Define routes
$router = new \Phalcon\Mvc\Router(false);
$router->setDefaultNamespace('Common\Controllers');
$router->notFound([
    "controllers" => "errors",
    "action" => "show404"
]);


/*
 * ---------------------------------------------------------------
 * COMMON Routes
 * ---------------------------------------------------------------
 */
/*
  $commonGroup->notFound([
  "controllers" => "errors",
  "action" => "show404"
  ]); */

$router->addGet('/', ['controller' => 'index', 'action' => 'index']);
$router->addGet('/pricing', ['controller' => 'pricing', 'action' => 'index']);
//$router->addGet('/login', ['controller' => 'login', 'action' => 'index']);
//$router->addPost('/login/do', ['controller' => 'login', 'action' => 'do']);
$router->addGet('/login/destroy', ['controller' => 'login', 'action' => 'destroy']);

//$router->addGet('/signup', ['controller' => 'signup', 'action' => 'index']);
//$router->addPost('/signup/do', ['controller' => 'signup', 'action' => 'do']);

/*
 * ---------------------------------------------------------------
 * DASHBOARD Routes
 * ---------------------------------------------------------------
 */

$dashboardGroup = new \Phalcon\Mvc\Router\Group(['namespace' => 'Aiden\Controllers']);
//Login
$dashboardGroup->addGet('/login', ['controller' => 'login', 'action' => 'index']);
$dashboardGroup->addPost('/login/do', ['controller' => 'login', 'action' => 'do']);
// Sign up
$dashboardGroup->addGet('/signup', ['controller' => 'signup', 'action' => 'index']);
$dashboardGroup->addPost('/signup/do', ['controller' => 'signup', 'action' => 'do']);
// Settings
$dashboardGroup->addGet('/account-profile', ['controller' => 'settings', 'action' => 'index']);
$dashboardGroup->add('/account-profile/:action', ['controller' => 'settings', 'action' => 1]);
$dashboardGroup->add('/account-profile/updateSeen', ['controller' => 'settings', 'action' => 'updateSeen']);
// Support
$dashboardGroup->addGet('/support', ['controller' => 'settings', 'action' => 'support']);
$dashboardGroup->add('/support/:action', ['controller' => 'settings', 'action' => 2]);
// Billing
$dashboardGroup->addGet('/billing', ['controller' => 'settings', 'action' => 'billing']);
$dashboardGroup->add('/billing/stripeApi', ['controller' => 'settings', 'action' => 'stripeApi']);
// Notifications
$dashboardGroup->addGet('/notifications', ['controller' => 'settings', 'action' => 'notifications']);
$dashboardGroup->add('/notifications/notificationsUpdate', ['controller' => 'settings', 'action' => 'notificationsUpdate']);

// User Leads
$dashboardGroup->addGet('/dashboard', ['controller' => 'index', 'action' => 'index']);
$dashboardGroup->addGet('/search', ['controller' => 'index', 'action' => 'search']);
$dashboardGroup->add('/leads/:action', ['controller' => 'leads', 'action' => 1]);
$dashboardGroup->addGet('/leads/{lead_id:[0-9]+}/:action', ['controller' => 'leads', 'action' => 2]);
$dashboardGroup->addGet('/leads', ['controller' => 'leads', 'action' => 'index']);
$dashboardGroup->addGet('/leads/saved', ['controller' => 'leads', 'action' => 'indexSaved']);

// User Councils
$dashboardGroup->addGet('/councils', ['controller' => 'councils', 'action' => 'index']);
$dashboardGroup->add('/councils/:action', ['controller' => 'councils', 'action' => 1]);
$dashboardGroup->addGet('/councils/{council_id:[0-9]+}/:action', ['controller' => 'councils', 'action' => 2]);

// User Phrases
$dashboardGroup->addGet('/phrases', ['controller' => 'phrases', 'action' => 'index']);
$dashboardGroup->add('/phrases/:action', ['controller' => 'phrases', 'action' => 1]);
$dashboardGroup->addGet('/phrases/{phrase_id:[0-9]+}/:action', ['controller' => 'phrases', 'action' => 2]);
$dashboardGroup->add('/phrases/create', ['controller' => 'phrases', 'action' => 'create']);
$dashboardGroup->addPost('/phrases/delete', ['controller' => 'phrases', 'action' => 'delete']);

// CRON: Public
$dashboardGroup->addGet('/cron/:action', ['controller' => 'cron', 'action' => 1]);


// DATATABLES: Public
$dashboardGroup->add('/datatables/:action', ['controller' => 'datatables', 'action' => 1]);


// POI
$dashboardGroup->addGet('/poi', ['controller' => 'poi', 'action' => 'index']);
$dashboardGroup->add('/poi/:action', ['controller' => 'poi', 'action' => 1]);

// Search
$dashboardGroup->addGet('/search', ['controller' => 'search', 'action' => 'index']);
$dashboardGroup->addGet('/search/map', ['controller' => 'search', 'action' => 'map']);
$dashboardGroup->add('/search/:action', ['controller' => 'search', 'action' => 1]);
$router->mount($dashboardGroup);


/*
 * ---------------------------------------------------------------
 * ADMIN Routes
 * ---------------------------------------------------------------
 */

// Administrator Routes
$adminGroup = new \Phalcon\Mvc\Router\Group(['namespace' => 'Aiden\Controllers\Admin']);
$adminGroup->setPrefix('/admin');

// Administrator Index
$adminGroup->addGet('[/]?', ['controller' => 'index', 'action' => 'index']);

// Administrator Leads
$adminGroup->addPost('/leads/save', ['controller' => 'leads', 'action' => "save"]);

$adminGroup->addGet('/leads/{lead_id:[0-9]+}/:action', ['controller' => 'leads', 'action' => 2]);
$adminGroup->add('/leads/:action', ['controller' => 'leads', 'action' => 1]);
$adminGroup->addGet('/leads', ['controller' => 'leads', 'action' => 'index']);

// Administrator Users
$adminGroup->addGet('/users', ['controller' => 'users', 'action' => 'index']);
$adminGroup->add('/users/{user_id:[0-9]+}/:action', ['controller' => 'users', 'action' => 2]);

// Administrator Phrases
$adminGroup->addGet('/phrases', ['controller' => 'phrases', 'action' => 'index']);
$adminGroup->addGet('/phrases/{phrase_id:[0-9]+}/:action', ['controller' => 'phrases', 'action' => 2]);
$adminGroup->addPost('/phrases/{phrase_id:[0-9]+}/edit', ['controller' => 'phrases', 'action' => 'edit']);
$adminGroup->addPost('/phrases/:action', ['controller' => 'phrases', 'action' => 1]);

// Administrator Councils
$adminGroup->add('/councils', ['controller' => 'councils', 'action' => "index"]);
$adminGroup->add('/councils/:action', ['controller' => 'councils', 'action' => 1]);

// Administrator Datatables
$adminGroup->add('/datatables/:action', ['controller' => 'datatables', 'action' => 1]);

// Mount the router groups
$router->mount($adminGroup);






return $router;
