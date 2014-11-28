<?php

namespace Elibrary\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

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
        $reservedbooks = $this->client->getReservedbooks();
        $mostviewed = $this->client->getMostviewed();
        //print_r($reservedbooks);exit;
        return $this->view->render('book/index.twig', [
            'categories' => $categories,
            'reservedbooks' => $reservedbooks,
            'mostviewed' => $mostviewed
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
        $reservedbooks = $this->client->getReservedbooks();
        $mostviewed = $this->client->getMostviewed();
        return $this->view->render('book/category.twig', [
            'books' => $books,
            'reservedbooks' => $reservedbooks,
            'mostviewed' => $mostviewed
        ]);
        /*        return $this->view->render('book/sidebar.twig', [
                    'books' => $books,
                ]);*/
    }


    public function search(Request $request)
    {
        $filter = $request->get('filter');
        $books = $this->client->getBooks([
            'query' => ['filter' => $filter]
        ]);
        $reservedbooks = $this->client->getReservedbooks();
        $mostviewed = $this->client->getMostviewed();
        return $this->view->render('book/search.twig', [
            'books' => $books,
            'reservedbooks' => $reservedbooks,
            'mostviewed' => $mostviewed,
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
        $reservedbooks = $this->client->getReservedbooks();
        $mostviewed = $this->client->getMostviewed();
        return $this->view->render('book/view.twig', [
            'book' => $book,
            'reservedbooks' => $reservedbooks,
            'mostviewed' => $mostviewed
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
                sprintf('%s/storage/books/%s.%s', $app['APP_DIR'], $localBookHash, $localBookExt),
                file_get_contents($book['book_file_url'])
            );
        }
        $reservedbooks = $this->client->getReservedbooks();
        $mostviewed = $this->client->getMostviewed();
        return $this->view->render('book/viewer.twig', [
            'book' => $book,
            'localBookFile' => $localBookFile,
            'localBookHash' => $localBookHash,
            'localBookExt' => $localBookExt,
            'reservedbooks' => $reservedbooks,
            'mostviewed' => $mostviewed,
        ]);
    }
}
