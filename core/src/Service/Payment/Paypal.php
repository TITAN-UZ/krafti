<?php

namespace App\Service\Payment;

use App\Container;
use App\Model\Order;

class Paypal extends Payment
{

    /**
     * @return \PayPal\Rest\ApiContext
     */
    protected function getApi()
    {
        $credentials = new \PayPal\Auth\OAuthTokenCredential(
            getenv('PAYPAL_TEST') ? getenv('PAYPAL_TEST_ID') : getenv('PAYPAL_ID'),
            getenv('PAYPAL_TEST') ? getenv('PAYPAL_TEST_SECRET') : getenv('PAYPAL_SECRET'),
        );

        $apiContext = new \PayPal\Rest\ApiContext($credentials);
        $apiContext->setConfig([
            'mode' => getenv('PAYPAL_TEST') ? 'sandbox' : 'live'
        ]);

        return $apiContext;
    }


    /**
     * @param Order $order
     *
     * @return string
     */
    public function getPaymentLink(Order $order)
    {
        $apiContext = $this->getApi();
        $site_url = getenv('SITE_URL');

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($order->cost);
        $amount->setCurrency('RUB');

        $item = new \PayPal\Api\Item();
        $item->setDescription('Оплата за курс занятий');
        $item->setPrice($order->cost);
        $item->setName($order->course->title);
        $item->setQuantity(1);
        $item->setUrl($site_url . 'courses/' . $order->course->id);
        $item->setCurrency('RUB');

        $items = new \PayPal\Api\ItemList();
        $items->addItem($item);

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setInvoiceNumber($order->id);
        $transaction->setDescription('Оплата за курс занятий "' . $order->course->title . '"');
        $transaction->setAmount($amount);
        $transaction->setNotifyUrl($site_url . 'api/web/payment');
        $transaction->setItemList($items);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls
            ->setReturnUrl($site_url . 'service/payment/success?InvId=' . $order->id)
            ->setCancelUrl($site_url . 'service/payment/failure?InvId=' . $order->id);

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($apiContext);

            return $payment->getApprovalLink();
        } catch (\Throwable $e) {
            $this->container->logger->error('Paypal error', ['data' => $e->getMessage()]);
        }

        return false;
    }


    /**
     * InvId=4&paymentId=PAYID-LVXT6EI36X040150V5542215&token=EC-76335975J2010960F&PayerID=DENSTH88A8QJY
     *
     * @param Order $order
     * @param array $params
     *
     * @return bool|null
     */
    public function finalize(Order $order, array $params)
    {
        $apiContext = $this->getApi();
        $payment = \PayPal\Api\Payment::get($params['paymentId'], $apiContext);
        $execution = new \PayPal\Api\PaymentExecution();
        $execution->setPayerId($params['PayerID']);

        try {
            $payment = $payment->execute($execution, $apiContext);
            $state = $payment->getState();
            if ($state == 'failed') {
                $this->container->logger->error('PayPal failure', [
                    'data' => [
                        'order' => $order->toArray(),
                        'payment' => $payment->toArray(),
                    ],
                ]);
            }

            return $state == 'created'
                ? null
                : $state == 'approved';
        } catch (\Throwable $e) {
            $this->container->logger->error('Paypal error', ['data' => $e->getMessage()]);
        }

        return false;
    }
}