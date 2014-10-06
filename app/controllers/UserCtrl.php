<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Api\ElibraryApiClient;
use Elibrary\Lib\Exception\ApiException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class UserCtrl extends BaseCtrl
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    public function main(Request $request)
    {
        if ($request->isMethod('post')) {
            $postData = $request->request->all();
            try {
                $this->client->authenticate($postData['unique_id'], $postData['password']);

                return $this->app->redirect($this->app['url_generator']->generate('user.dashboard'));
            } catch (ApiException $e) {
                $this->alerts->set('errors', $e->getMessage());

                return $this->app->redirect($this->app['url_generator']->generate('user.main'));
            }
        }

        return $this->view->render('user/main.twig');
    }

    /**
     * @return string
     */
    public function dashboard()
    {
        $randomBook = $this->client->getRandomBook();
        //print_r($randomBook);exit;
        return $this->view->render(
            'user/dashboard.twig',
            [
                'randomBook' => $randomBook
            ]
        );
    }

    public function bill()
    {
        return $this->view->render('user/bill.twig');
    }
}
