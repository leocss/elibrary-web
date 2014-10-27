<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Api\ElibraryApiClient;
use Elibrary\Lib\Exception\ApiException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class ElectronicTestCtrl extends BaseCtrl
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    public function index(Request $request)
    {
        $courses = $this->client->getEtestCourses();

        return $this->view->render(
            'etest/index.twig',
            [
                'courses' => $courses
            ]
        );
    }

    /**
     * @param Request $request
     * @param $course_id
     * @return string
     */
    public function session(Request $request, $course_id)
    {
        $user = $this->client->getSessionUser();

        // First create a session
        $session = $this->client->createEtestSession(
            [
                'body' => [
                    'course_id' => $course_id,
                    'user_id' => $user['id'],
                    'question_limit' => 20
                ]
            ]
        );

        // The session record returned from the previous request is
        // used to perform another request to retrieve both the session details
        // and all the questions associated with this session.
        $session = $this->client->getEtestSession(
            $session['id'],
            [
                'query' => [
                    'include' => 'questions'
                ]
            ]
        );

        return $this->view->render(
            'etest/session.twig',
            [
                'session' => $session
            ]
        );
    }
}
