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
        $categories = $this->client->getCategories([
            'include' => 'books',
            'books_limit' => 12
        ]);

        return $this->view->render('book/index.twig', [
            'categories' => $categories
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
    public function categories()
    {
        $books = $this->client->getBooks();
        return $this->view->render('book/categories.twig', [
            'categories' => $categories,
            'books' => $books,

        ]);
    }
    public function search()
    {
        $books = $this->client->getBooks();
        return $this->view->render('book/search.twig', [
                'books' => $books,
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
