<?php

namespace Elibrary\Controllers;

use Silex\Application;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class UserCtrl extends BaseCtrl
{
    /**
     * @return string
     */
    public function main()
    {
        return $this->view->render('user/main.twig');
    }

    /**
     * @return string
     */
    public function dashboard()
    {
        return $this->view->render('user/dashboard.twig');
    }
}
