<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Exception\ApiException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class ArticleCtrl extends BaseCtrl
{
    public function index(Request $request)
    {
        $articles = $this->client->getArticles();

		    return $this->view->render('article/index.twig', [
            'articles' => $articles
        ]);
    }

    public function view($id)
    {
        $article = $this->client->getArticle($id);

        return $this->view->render('article/view.twig', [
            'article' => $article
        ]);
    }
}
