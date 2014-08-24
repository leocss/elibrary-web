<?php

namespace Elibrary\Lib\Api;

use Elibrary\Lib\Exception\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Api Client for elibrary
 * Note: This elibrary api client is injected in all
 * controller instances that extends the BaseCtrl controller
 * and can be accessed using
 * <code>
 *  $this->client->someMethod();
 * </code>
 *
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class ElibraryApiClient extends Client
{
    const API_URL = 'https://api.elibrary.app';

    /**
     * @var Session
     */
    protected $session;

    protected $clientId = null;

    protected $clientSecret = null;

    protected $accessToken = null;

    public function __construct(Session $session)
    {
        $this->session = $session;

        parent::__construct(['base_url' => static::API_URL]);
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Helper method to manually set the access token.
     *
     * @param $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param $studentId
     * @param $password
     * @return mixed
     */
    public function authenticate($studentId, $password)
    {
        $accessTokenData = $this->send($this->buildRequest('GET', '/oauth2/token', [
            'body' => [
                'student_id' => $studentId,
                'password' => $password,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ]
        ]));

        $this->session->set('api.token', [
            'access_token' => $accessTokenData['access_token'],
            'expire_time' => $accessTokenData['expire_time'],
        ]);

        $user = $this->getUser('me');

        $this->session->set('api.user', [
            'id' => $user['id'],
            'email' => $user['email']
        ]);

        return $user;
    }

    public function getSessionUser()
    {
        if ($this->session->has('api.user')) {
            return $this->session->get('api.user');
        }

        return false;
    }

    /**
     * @param int $id The user id.
     *  Note: Passing 'me' as the ID will return the user that was
     *  authenticated during this session.
     * @return mixed
     */
    public function getUser($id)
    {
        return $this->send($this->buildRequest('GET', sprintf('/users/%s', $id)));
    }

    /**
     * @return array
     */
    public function getBooks()
    {
        $response = $this->send($this->buildRequest('GET', '/books'));

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

    /**
     * @param $method
     * @param $endpoint
     * @param $opts
     * @return \GuzzleHttp\Message\RequestInterface
     */
    protected function buildRequest($method, $endpoint, $opts = [])
    {
        $request = $this->createRequest($method, $endpoint, $opts);

        if (($accessTokenData = $this->session->get('api.token')) != null) {
            // Check if the access token data is available in the session.
            // If there is access token information, retrieve it and
            // pass it along the request sent to the api server
            // instead of having to do this manually for every api request.
            $request->setHeader('Authorization', sprintf('Bearer %s', $accessTokenData['access_token']));
        }

        // Set Content-Type header to application/json
        $request->setHeader('Content-Type', 'application/json');

        return $request;
    }

    /**
     * @param RequestInterface $request
     * @returns ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        try {
            $response = parent::send($request)->json();
            if (isset($response['error'])) {
                // If the api server returns an error, throw an api exception
                // with the message of the error returned by the api server
                throw new ApiException($response['error']['message']);
            }
        } catch (TransferException $e) {
            // Catch all exceptions thrown by the guzzle http client library
            // converting the exception to our own ApiException for easier
            // error handling
            throw new ApiException($e->getMessage());
        }

        return $response['data'];
    }
}
