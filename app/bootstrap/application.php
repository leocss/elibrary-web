<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Silex\Application();

/**
 * Define App Config
 */
$app['debug'] = true;
$app['app.lib.api.elibrary_client_id'] = 'xxx';
$app['app.lib.api.elibrary_client_secret'] = 'xxx';

/**
 * Register Services
 */
$app->register(new \Silex\Provider\ServiceControllerServiceProvider());
$app->register(new \Silex\Provider\SessionServiceProvider());
$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views'
]);

/**
 * Register Error Handlers
 */
$app->error(function (\Elibrary\Lib\Exception\ApiException $exception) use ($app) {
    return $app['twig']->render('error/api.twig');
});

/**
 * Register App Services
 */
$app['app.lib.ElibraryApiClient'] = $app->share(function () use ($app) {
    $client = new \Elibrary\Lib\Api\ElibraryApiClient($app['session']);
    $client->setClientId($app['app.lib.api.elibrary_client_id']);
    $client->setClientSecret($app['app.lib.api.elibrary_client_secret']);

    return $client;
});

$app['app.GlobalCtrlDependencies'] = $app->share(function () use ($app) {
    return [
        'app' => $app,
        'view' => $app['twig'],
        'client' => $app['app.lib.ElibraryApiClient']
    ];
});

/**
 * Register Controllers
 */
$app['app.controllers.PrintJob'] = $app->share(function () use ($app) {
    return new \Elibrary\Controllers\PrintJobCtrl($app['app.GlobalCtrlDependencies']);
});

$app['app.controllers.Book'] = $app->share(function () use ($app) {
    return new Elibrary\Controllers\BookCtrl($app['app.GlobalCtrlDependencies']);
});

$app['app.controllers.User'] = $app->share(function () use ($app) {
    return new \Elibrary\Controllers\UserCtrl($app['app.GlobalCtrlDependencies']);
});

// Application Routes
$app->get('/', 'app.controllers.User:main');
$app->get('/dashboard', 'app.controllers.User:dashboard');
$app->get('/books', 'app.controllers.Book:index');
$app->get('/books/{id}', 'app.controllers.Book:view');
$app->get('/print-jobs', 'app.controllers.PrintJob:index');
$app->get('/print-jobs/{id}', 'app.controllers.PrintJob:view');
$app->match('/print-jobs/create', 'app.controllers.PrintJob:create');

return $app;