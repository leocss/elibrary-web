<?php

namespace Elibrary\Lib\Api;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Api Client for elibrary
 * Usage:
 * <code>
 *  $client = new ElibraryClient();
 *  *
 *  $client->getBooks();
 *  $client->getBook(35445);
 *  *
 * </code>
 *
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class ElibraryClient extends Client
{
    public function __construct(Session $session)
    {
        $defaults = [];
        if (($accessToken = $session->get('access_token')) != null) {
            // If the access token is available in the session, we
            // retrieve it and pass it along the request sent to the api
            // server instead of having to do this manually for every api request.
            $defaults['headers'] = ['Authorization' => sprintf('Bearer %s', $accessToken)];
        }

        parent::__construct([
            'base_url' => ['https://api.elibrary.app', []],
            'defaults' => $defaults
        ]);
    }

    /**
     * @return array
     */
    public function getBooks()
    {
        return [];
    }

    /**
     * @param int $bookId
     * @return array
     */
    public function getBook($bookId)
    {
        return [];
    }
}
