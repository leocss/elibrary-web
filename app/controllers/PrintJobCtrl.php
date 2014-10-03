<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Exception\ApiException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class PrintJobCtrl extends BaseCtrl
{
    public function index(Request $request)
    {
        $printJobs = $this->client->getPrintJobs();

        if ($request->isMethod('post')) {
            switch (key($request->request->get('action'))) {
                case 'create_job':
                    try {
                        $response = $this->client->createPrintJob($request->request->all());

                        return $this->app->redirect(
                            $this->app['url_generator']->generate('printJobs.view', ['id' => $response['id']])
                        );
                    } catch (ApiException $e) {
                        $this->alerts->set('errors', $e->getMessage());

                        return $this->app->redirect(
                            $this->app['url_generator']->generate('printJobs.index')
                        );
                    }
                    break;
            }
        }

        return $this->view->render('print-job/index.twig', compact('printJobs'));
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $response = $this->client->createPrintJob($request->request->all());
            } catch (ApiException $e) {

            }

            if (isset($response['id'])) {
                return $this->app->redirect(
                    $this->app['url_generator']->generate('printJobs.view', ['id' => $response['id']])
                );
            }

            $this->alerts->set('errors', $e->getMessage());
        }

        return $this->view->render('print-job/create.twig');
    }

    public function view(Request $request, $id)
    {
        $printJob = $this->client->getPrintJob($id);

        if ($request->isMethod('post')) {
            switch (key($request->request->get('action'))) {
                case 'upload_document':
                    try {
                        $response = $this->client->uploadPrintJobDocument($id, $request->files->get('document'));

                        return $this->app->redirect(
                            $this->app['url_generator']->generate('printJobs.view', ['id' => $id])
                        );
                    } catch (ApiException $e) {
                        $this->alerts->set('errors', $e->getMessage());

                        return $this->app->redirect(
                            $this->app['url_generator']->generate('printJobs.view', ['id' => $id])
                        );
                    }
                    break;
                case 'delete_document':
                    $this->client->deletePrintJobDocument($id, $request->request->get('document_id'));

                    return $this->app->redirect(
                        $this->app['url_generator']->generate('printJobs.view', ['id' => $id])
                    );
                    break;
            }
        }

        return $this->view->render('print-job/view.twig', compact('printJob'));
    }
}
