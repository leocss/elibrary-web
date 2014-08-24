<?php

namespace Elibrary\controllers;

use Elibrary\Lib\Api\ElibraryClient;
use Silex\Application;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class UserCtrl extends BaseCtrl
{
    public function main()
    {
        return $this->view->render('user/main.twig');
    }

    public function dashboard()
    {
        return $this->view->render('user/dashboard.twig');
    }
}
