<?php

namespace Elibrary\controllers;

use Elibrary\Lib\Api\ElibraryApiClient;
use Silex\Application;
use Twig_Environment;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class BaseCtrl
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var ElibraryApiClient
     */
    protected $client;

    /**
     * @var Twig_Environment
     */
    protected $view;

    public function __construct($controllerDependencies)
    {
        $this->app = $controllerDependencies['app'];
        $this->view = $controllerDependencies['view'];
        $this->client = $controllerDependencies['client'];
    }
}
