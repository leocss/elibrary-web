<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Api\ElibraryApiClient;
use Elibrary\Lib\Exception\ApiException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


/**
 * @author Laju Morrison <morrelinko@gmail.com>
 * @author Elijah Abolaji <tyabolaji@gmail.com>
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
        $sessionName = sprintf('etest.session_%s', $course_id);
        // to delete existing session to create a new one so all questions can be seen
        $this->session->remove($sessionName);

        // Check if we have a cached session for this etest session.
        if (!$this->session->has($sessionName)) {
            // If none exists, we tell the api server to begin a new
            // question session for this user in the selected course.
            $session = $this->client->createEtestSession(
                [
                    'body' => [
                        'course_id' => $course_id,
                        'user_id' => $user['id'],
                        'question_limit' => 20
                    ]
                ]
            );

            // Save the etest-session info into our server session.
            // So we can reference to it later.
            $this->session->set($sessionName, $session);
        } else {
            // A session is already going on for this user in this particular course,
            // Just usee it...
            $session = $this->session->get($sessionName);
        }

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

        // Check if the form is submitted. (ie lookout for a POST request)
        if ($request->isMethod('post')) {
            // Retreive answers for all questions
            $answers = $request->request->get('question');

            // Send the results to the api server to process.
            $response = $this->client->submitEtestSessionResult($session['id'], $answers);

            if (isset($response['success'])) {
                // Clear the session saving our etest session info
                $this->session->remove($sessionName);

                // If everything goes well over there in the api...
                // we redirect the user to the results page for this session.
                // Note: the api server also takes care of closing the etest session.
                return $this->app->redirect(
                    $this->app['url_generator']->generate(
                        'etest.result',
                        [
                            'session_id' => $session['id']
                        ]
                    )
                );
            }
        }

        return $this->view->render(
            'etest/session.twig',
            [
                'session' => $session
            ]
        );
    }

    public function result($session_id)
    {
        return $this->view->render('etest/result.twig');
    }



    public function summary()
    {

    }
}
