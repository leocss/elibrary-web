<?php

namespace Elibrary\Controllers;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class BillingCtrl
 * @package Elibrary\controllers
 *
 * @author Laju Morrison <morrelinko@gmail.com>
 * @author Tosin Akomolafe <gettosin4me@gmail.com>
 */
class BillingCtrl extends BaseCtrl
{
    public function index(Request $request)
    {
        $transactions = $this->client->getPaymentTransactions();

        return $this->view->render('billing/index.twig', [
            'transactions' => $transactions
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
        var_dump($request->request->all());

        return $this->view->render('payment/index.twig');
    }
}