<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;
$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views'
]);

$app->get('/', function () use ($app) {
    return $app['twig']->render('user/lock-screen.twig');
});

$app->get('/dashboard', function () use ($app) {
    return $app['twig']->render('user/dashboard.twig');
});

$app->get('/books', 'Elibrary\BookCtrl');

$app->get('/books/{id}', function ($id) use ($app) {
        $book = json_decode(file_get_contents(sprintf('http://127.0.0.1:4000/books/%d', $id)), true);

        return $app['twig']->render('book/view.twig', [
            'book' => $book
        ]);
    }
);

$app->get('/printjobs', function () use ($app) {
    return $app['twig']->render('printjob/index.twig');
});

$app->match('/printjobs/create', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
    if ($request->isMethod('post')) {
        var_dump($request->request->all());
    }

    return $app['twig']->render('printjob/create.twig');
});

$app->match('/printjobs/{id}', function () use ($app) {
    return $app['twig']->render('printjob/view.twig');
});

return $app;