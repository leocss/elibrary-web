<?php

namespace Elibrary\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class AjaxCtrl extends BaseCtrl
{
    /**
     * @param $article_id
     * @return array|string
     */
    public function likeArticle($article_id)
    {
        $response = $this->client->likePost($article_id);
        if (isset($response['error'])) {
            return json_encode(['error_message' => $response['error']['message']]);
        }

        return json_encode(['id' => $article_id, 'success' => true]);
    }

    /**
     * @param $article_id
     * @return array|string
     */
    public function unlikeArticle($article_id)
    {
        $response = $this->client->unlikePost($article_id);
        if (isset($response['error'])) {
            return json_encode(['error_message' => $response['error']['message']]);
        }

        return json_encode(['id' => $article_id, 'success' => true]);
    }

    /**
     * @param $book_id
     * @return array|string
     */
    public function unlikeBook($book_id)
    {
        $response = $this->client->unlikeBook($book_id);
        if (isset($response['error'])) {
            return json_encode(['error_message' => $response['error']['message']]);
        }

        return json_encode(['id' => $book_id, 'success' => true]);
    }
}
