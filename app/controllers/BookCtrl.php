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
            'query' => [
                'include' => 'books',
                'books_limit' => 4
            ]
        ]);

        //print_r($categories);exit;

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

    public function category($id)
    {
        $books = $this->client->getBooks([
            'query' => [
                'category' => $id,
                'limit' => 18,
                'include' => 'category'
            ]
        ]);

        return $this->view->render('book/category.twig', [
            'books' => $books,
        ]);
        /*        return $this->view->render('book/sidebar.twig', [
                    'books' => $books,
                ]);*/
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

    /**
     * @param Application $app
     * @param $id
     * @return string
     */
    public function viewer(Application $app, $id)
    {
        $book = $this->client->getBook($id);
        $localBookFile = null;
        $localBookHash = null;
        $localBookExt = null;
        if (!empty($book['file_name'])) {
            $localBookHash = md5($book['book_file_url']);
            $localBookExt = pathinfo($book['book_file_url'], PATHINFO_EXTENSION);
            $localBookFile = file_put_contents(
                sprintf('%s/storage/books/%s.%s', $app['APP_DIR'], $localBookHash, $localBookExt)
            );
        }

        return $this->view->render('book/viewer.twig', [
            'book' => $book,
            'localBookFile' => $localBookFile,
            'localBookHash' => $localBookHash,
            'localBookExt' => $localBookExt
        ]);
    }
}
