<?php

use Elibrary\Lib\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Elibrary\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Silex\Application();

/**
 * Define App Config
 */
$app['debug'] = true;
$app['app.lib.api.elibrary_client_id'] = '9d81c76533b0407d7c52e0ebd5ba2dcf';
$app['app.lib.api.elibrary_client_secret'] = 'ebe661a508c4fc56a69643cb8087b005';

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
        if ($exception->getErrorCode() == 'invalid_token') {
            // If the api erro is an 'invalid_token' response, then the user
            // needs to be logged out so we can generate another valid token.
            return $app->redirect($app['url_generator']->generate('user.main'));
        }

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
        $client = new \Elibrary\Lib\Api\ElibraryApiClient(
            $app, $app['session'], [
                'endpoint' => 'http://127.0.0.1:4000'
            ]
        );
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
 * Register Handlers
 */

$app->before(
    function (Request $request) use ($app) {
        $app['base_url'] = $request->getUriForPath('/');

        $app['url_segments'] = array_filter(explode('/', trim($request->getPathInfo(), '/ ')));

        $app['twig']->addFunction(
            new Twig_SimpleFunction(
                'is_module', function ($name) use ($app) {
                    return (isset($app['url_segments'][0]) && $app['url_segments'][0] == $name);
                }
            )
        );
    },
    Silex\Application::LATE_EVENT
);

$app->before(
    function (Request $request) use ($app) {

        // Register a global 'errors' variable that will be available in all
        // views of this library application...
        $app['twig']->addGlobal('errors', $app['session']->getFlashBag()->get('errors'));

        $elibraryClient = $app['app.lib.ElibraryApiClient'];
        // Ensure that the user is logged in...
        if ($request->getPathInfo() != '/') { // Prevent from auth check if on main page
            if (!$elibraryClient->getSessionUser()) {
                return $app->redirect($app['url_generator']->generate('user.main'));
            }
        }

        $app['ROOT_DIR'] = __DIR__ . '/../..';
        $app['APP_DIR'] = $app['ROOT_DIR'] . '/app';
        $app['default_article_image'] = $app['base_url'] . 'assets/img/sample-book-preview.png';
        $app['default_book_image'] = $app['base_url'] . 'assets/img/sample-book-preview.png';
        $app['default_user_image'] = $app['base_url'] . 'assets/img/user/default-user-image.png';
    },
    Silex\Application::LATE_EVENT
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

$app['app.controllers.ElectronicTest'] = $app->share(
    function () use ($app) {
        return new Controllers\ElectronicTestCtrl($app['app.GlobalCtrlDependencies']);
    }
);

$app['app.controllers.Billing'] = $app->share(
    function () use ($app) {
        return new Controllers\BillingCtrl($app['app.GlobalCtrlDependencies']);
    }
);

$app['app.controllers.Article'] = $app->share(
    function () use ($app) {
        return new Controllers\ArticleCtrl($app['app.GlobalCtrlDependencies']);

    }
);

$app['app.controllers.Billing'] = $app->share(
    function () use ($app) {
        return new Controllers\BillingCtrl($app['app.GlobalCtrlDependencies']);
    }
);

$app['app.controllers.Ajax'] = $app->share(
    function () use ($app) {
        return new Controllers\AjaxCtrl($app['app.GlobalCtrlDependencies']);
    }
);

// Application Routes

// General
$app->match('/', 'app.controllers.User:main')->method('GET|POST')->bind('user.main');
$app->get('/dashboard', 'app.controllers.User:dashboard')->bind('user.dashboard');

// Books
$app->get('/books', 'app.controllers.Book:index')->bind('books.index');
$app->get('/books/search', 'app.controllers.Book:search')->bind('books.search');
$app->get('/books/category/{id}', 'app.controllers.Book:category')->bind('books.category');
$app->get('/books/{id}/read', 'app.controllers.Book:viewer')->bind('books.viewer');
$app->get('/books/{id}', 'app.controllers.Book:view')->bind('books.view');

// Print Jobs
$app->match('/print-jobs', 'app.controllers.PrintJob:index')->bind('printJobs.index')->method('GET|POST');
$app->match('/print-jobs/create', 'app.controllers.PrintJob:create')->bind('printJobs.create')->method('GET|POST');
$app->match('/print-jobs/{id}', 'app.controllers.PrintJob:view')->bind('printJobs.view')->method('GET|POST');

// Article
$app->get('/articles', 'app.controllers.Article:index')->bind('articles.index');
$app->get('/articles/{id}', 'app.controllers.Article:view')->bind('articles.view');

// Billing
$app->get('/billing', 'app.controllers.Billing:index')->bind('billing.index');
$app->get('/billing/checkout', 'app.controllers.Billing:checkout')->bind('billing.checkout');
$app->get('/billing/checkout/transactions', 'app.controllers.Billing:checkout')->bind('billing.checkout');
$app->get('/billing/checkout/fund', 'app.controllers.Billing:checkout')->bind('billing.checkout');
$app->get('/billing/checkout/pay', 'app.controllers.Billing:checkout')->bind('billing.checkout');

//payment
$app->get('/payment/gateway', 'app.controllers.Billing:payment')->bind('billing.payment');

// Electronic Test
$app->match('/etest', 'app.controllers.ElectronicTest:index')->bind('etest.index')->method('GET|POST');
$app->get('/etest/{course_id}', 'app.controllers.ElectronicTest:viewCourse')->bind('etest.view-course');
$app->match('/etest/session/course-{course_id}', 'app.controllers.ElectronicTest:session')->bind('etest.session')->method('GET|POST');
$app->get('/etest/test', 'app.controllers.ElectronicTest:test')->bind('etest.test');
$app->get('/etest/{id}', 'app.controllers.ElectronicTest:test1')->bind('etest.test1');
$app->get('/etest/result/{session_id}', 'app.controllers.ElectronicTest:result')->bind('etest.result');

// Ajax Stuffs
$app->post('/ajax/articles/{article_id}/like', 'app.controllers.Ajax:likeArticle');
$app->post('/ajax/articles/{article_id}/unlike', 'app.controllers.Ajax:unlikeArticle');
$app->post('/ajax/books/{book_id}/like', 'app.controllers.Ajax:likeBook');
$app->post('/ajax/books/{book_id}/unlike', 'app.controllers.Ajax:unlikeBook');
$app->post('/ajax/books/{book_id}/view', 'app.controllers.Ajax:viewBook');

return $app;