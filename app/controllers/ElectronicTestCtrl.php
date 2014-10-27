<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Api\ElibraryApiClient;
use Elibrary\Lib\Exception\ApiException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
 // $question = require ('../storage/data/exam.json');
  //$app['question'] = $question;
//$_POST= post;


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

        // $myTest = [];
         //$myTest =[];
        //$myTest = question.quest;

        return $this->view->render('etest/test.twig',[
           // 'question' => $myTest

        ]);

    }


    public function result()
    {


        return $this->view->render('etest/result.twig');

    }

    public function test1($id)
    {
               $etest_courses = $this->client->getEtest_courses($id);

        return $this->view->render(
            'etest/test1.twig',
            [
                'etest_courses' => $etest_courses
            ]
        );
    }

    public function summary()
    {



    }


}
