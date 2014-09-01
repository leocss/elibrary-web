<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Api\ElibraryApiClient;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Tosin Akomolafe <gettosin4me@gmail.com>
 */
class ExamCtrl extends BaseCtrl
{
    public function index()
    {
        return $this->view->render('exam/index.twig');
    }
    public function main()
    {
        return $this->view->render('exam/main.twig');
    }
}

 