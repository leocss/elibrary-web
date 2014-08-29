<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Api\ElibraryApiClient;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class UserCtrl extends BaseCtrl
{
    /**
     * @return string
     */
    public function main(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($post = $request->request->all()) {
                if ($this->client->authenticate($post['val']['email'], $post['val']['password'])) {
                    echo 'tosin';
                }
            }
        }

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
