<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Exception\ApiException;
use Elibrary\Lib\Exception\AppException;
use Silex\Application;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class BookCtrl extends BaseCtrl
{
    /**
     * Endpoint that displays list of books
     *
     * @return mixed
     */
    public function index()
    {
        $books = $this->client->getBooks();

        return $this->view->render('book/index.twig', [
            'books' => $books
        ]);
    }

    /**
     * Endpoint to view a book
     *
     * @param int $id
     * @return mixed
     */
    public function view($id)
    {
        $book = $this->client->getBook($id);

        return $this->view->render('book/view.twig', [
            'book' => $book
        ]);
    }
}
