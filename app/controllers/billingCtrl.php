<?php

namespace Elibrary\Controllers;

use Elibrary\Lib\Api\ElibraryApiClient;
use Elibrary\Lib\Exception\ApiException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class BillingCtrl extends BaseCtrl
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    public function index(Request $request)
    {
        $transactions = $this->client->getPaymentTransactions();
        $user = $this->client->getSessionUser();

        return $this->view->render('billing/index.twig', [
            'transactions' => $transactions,
            'user' => $user,
        ]);
    }

    /**
     * @return string
     */
    public function checkout(Request $request)
    {
        $transactions = $this->client->getPaymentTransactions();
        $user = $this->client->getSessionUser();

        if ($request->isMethod("POST")) {
            var_dump(array_sum($request->request->get('amount')));
        }

        return $this->view->render('billing/checkout.twig', [
            'transactions' => $transactions,
            'user' => $user,
        ]);
    }

    public function payment(Request $request)
    {
        return $this->view->render('payment/index.twig');
    }

    public function manage()
    {
        return $this->view->render('payment/manage.twig');
    }
}
