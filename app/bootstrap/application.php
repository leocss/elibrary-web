<?php

use Elibrary\Lib\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Elibrary\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Silex\Application();

/**
 * Register Handlers
 */
$app->before(
    function (Request $request) use ($app) {
        $app['base_url'] = $request->getUriForPath('/');
    },
    Silex\Application::EARLY_EVENT
);

$app->before(
    function (Request $request) use ($app) {
        // Register a global 'errors' variable that will be available in all
        // views of this library application...
        $app['twig']->addGlobal('errors', $app['session']->getFlashBag()->get('errors'));
    },
    Silex\Application::LATE_EVENT
);

/**
 * Define App Config
 */
$app['debug'] = true;
$app['app.lib.api.elibrary_client_id'] = 'test_client';
$app['app.lib.api.elibrary_client_secret'] = 'test_secret';

/**
 * Register Services
 */
$app->register(new \Silex\Provider\ServiceControllerServiceProvider());
$app->register(new \Silex\Provider\SessionServiceProvider());
$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());

$app->register(
    new \Silex\Provider\TwigServiceProvider(),
    [
        'twig.path' => __DIR__ . '/../views'
    ]
);

/**
 * Register Error Handlers
 */

// When an Api error occurs
$app->error(
    function (ApiException $exception) use ($app) {
        return $app['twig']->render(
            'error/api.twig',
            [
                'exception' => $exception
            ]
        );
    }
);

// When a route does not exists
$app->error(
    function (NotFoundHttpException $exception) use ($app) {
        return $app['twig']->render('error/not-found.twig');
    }
);

// All other exceptions
$app->error(
    function (Exception $exception) use ($app) {
        return $app['twig']->render(
            'error/server.twig',
            [
                'exception' => $exception
            ]
        );
    }
);

/**
 * Register App Services
 */
$app['app.lib.ElibraryApiClient'] = $app->share(
    function () use ($app) {
        $client = new \Elibrary\Lib\Api\ElibraryApiClient($app['session'], [
            'endpoint' => 'http://127.0.0.1:4000'
        ]);
        $client->setClientId($app['app.lib.api.elibrary_client_id']);
        $client->setClientSecret($app['app.lib.api.elibrary_client_secret']);

        return $client;
    }
);

$app['app.GlobalCtrlDependencies'] = $app->share(
    function () use ($app) {
        return [
            'app' => $app,
            'view' => $app['twig'],
            'client' => $app['app.lib.ElibraryApiClient']
        ];
    }
);

/**
 * Register Controllers
 */
$app['app.controllers.PrintJob'] = $app->share(
    function () use ($app) {
        return new Controllers\PrintJobCtrl($app['app.GlobalCtrlDependencies']);
    }
);

$app['app.controllers.Book'] = $app->share(
    function () use ($app) {
        return new Controllers\BookCtrl($app['app.GlobalCtrlDependencies']);
    }
);

$app['app.controllers.User'] = $app->share(
    function () use ($app) {
        return new Controllers\UserCtrl($app['app.GlobalCtrlDependencies']);
    }
);

$app['app.controllers.Exam'] = $app->share(
    function () use ($app) {
        return new Controllers\ExamCtrl($app['app.GlobalCtrlDependencies']);
    }
);

// Application Routes

$app->match('/', 'app.controllers.User:main')->method('GET|POST')->bind('user.main');
$app->get('/dashboard', 'app.controllers.User:dashboard')->bind('user.dashboard');
$app->get('/e-exam', 'app.controllers.Exam:index')->bind('exam.index');
$app->get('/examination', 'app.controllers.Exam:main')->bind('exam.main');
$app->get('/bill', 'app.controllers.User:bill')->bind('user.bill');
$app->get('/books', 'app.controllers.Book:index')->bind('books.index');
$app->get('/books/{id}', 'app.controllers.Book:view')->bind('books.view');
$app->get('/print-jobs', 'app.controllers.PrintJob:index')->bind('printJobs.index');
$app->match('/print-jobs/create', 'app.controllers.PrintJob:create')->bind('printJobs.create')->method('GET|POST');
$app->get('/print-jobs/{id}', 'app.controllers.PrintJob:view')->bind('printJobs.view');

return $app;