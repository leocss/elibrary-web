<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views'
]);

/**
 * Register Services
 */
$app['app.lib.ElibraryClient'] = $app->share(function () use ($app) {
    return new \Elibrary\Lib\Api\ElibraryClient($app['session']);
});

$app['app.DependencyCollection'] = $app->share(function () use ($app) {
    return [
        'view' => $app['twig'],
        'client' => $app['lib.ElibraryClient']
    ];
});

/**
 * Register Controllers
 */
$app['app.controllers.PrintJob'] = $app->share(function () use ($app) {
    return new \Elibrary\Controllers\PrintJobCtrl($app['app.DependencyCollection']);
});

$app['app.controllers.Book'] = $app->share(function () use ($app) {
    return new Elibrary\Controllers\BookCtrl($app['app.DependencyCollection']);
});

$app['app.controllers.User'] = $app->share(function () use ($app) {
    return new \Elibrary\controllers\UserCtrl($app['app.DependencyCollection']);
});

// Routes
$app->get('/', 'app.controllers.User:main');
$app->get('/dashboard', 'app.controllers.User:dashboard');
$app->get('/books', 'app.controllers.Book:index');
$app->get('/books/{id}', 'app.controllers.Book:view');
$app->get('/print-jobs', 'app.controllers.PrintJob:index');
$app->get('/print-jobs/{id}', 'app.controllers.PrintJob:view');
$app->match('/print-jobs/create', 'app.controllers.PrintJob:create');

return $app;