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

        return $this->view->render('billing/index.twig', [
            'transactions' => $transactions,
        ]);
    }

    /**
     * @return string
     */
    public function checkout()
    {
        $transactions = $this->client->getPaymentTransactions();

        return $this->view->render('billing/checkout.twig', [
            'transactions' => $transactions
        ]);
    }
}
