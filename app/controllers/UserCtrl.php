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
        return $this->view->render('user/dashboard.twig', [
            'randomBook' => $this->client->getRandomBook(),
            'recentArticles' => $this->client->getPosts(['query' => ['limit' => 5]]),
            'likedBooks' => $this->client->getBooks(['query' => ['stat' => '5_most_liked']])
        ]);
    }

    /**
     * @return string
     */
    public function logout()
    {
        if($this->app['session']->get('api.user')){
            $this->client->logout();
            return $this->app->redirect($this->app['url_generator']->generate('user.main'));
        }
    }
}
