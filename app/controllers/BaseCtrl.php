<?php

namespace Elibrary\controllers;

use Elibrary\Lib\Api\ElibraryClient;
use Twig_Environment;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class BaseCtrl
{

    /**
     * @var ElibraryClient
     */
    protected $client;

    /**
     * @var Twig_Environment
     */
    protected $view;

    public function __construct($controllerServiceCollection)
    {
        $this->view = $controllerServiceCollection['view'];
        $this->client = $controllerServiceCollection['client'];
    }
}
