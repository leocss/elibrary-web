<?php

namespace Elibrary\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class PrintJobCtrl extends BaseCtrl
{
    public function index()
    {
        return $this->view->render('print-job/index.twig');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($this->client->createPrintJob($request->)) {

            }
        }

        return $this->view->render('print-job/create.twig');
    }

    public function view()
    {
        return $this->view->render('print-job/view.twig');
    }
}
