<?php

namespace Elibrary\Controllers;

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
            'books' => $books,
        ]);
    }

    public function template()
    {
        $books = $this->client->getBooks();
        return $this->view->render('book/template.twig', [
            'books' => $books,
            'name' => "DICKSON"
        ]);
    }
    public function category()
    {
        $books = $this->client->getBooks();
        return $this->view->render('book/category.twig', [
            'books' => $books,
            'name' => "DICKSON"
        ]);
    }
    public function search()
    {
        $books = $this->client->getBooks();
        return $this->view->render('book/search.twig', [
                'books' => $books,
                'name' => "DICKSON"
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
