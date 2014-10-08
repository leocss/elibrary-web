<?php

namespace Elibrary\Controllers;

use Silex\Application;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class BillingCtrl extends BaseCtrl
{
    /**
     * Endpoint that displays list of books
     *
     * @return mixed
     */
    public function index()
    {
        $books = $this->client->getBooks();

        return $this->view->render('billing/index.twig', [
                'books' => $books,
            ]);
    }
}
