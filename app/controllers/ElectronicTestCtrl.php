<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Api\ElibraryApiClient;
use Elibrary\Lib\Exception\ApiException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Elijah Abolaji <tyabolaji@gmail.com>
 */
class ElectronicTestCtrl extends BaseCtrl
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    public function index(Request $request)
    {
        return $this->view->render('etest/index.twig');
    }


    public function  test()
    {

        return $this->view->render('etest/test.twig');

    }


    public function result()
    {




    }


    public function resume()
    {



    }

    public function summary()
    {



    }


}
