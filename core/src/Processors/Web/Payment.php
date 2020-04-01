<?php

namespace App\Processors\Web;

use App\Model\Order;
use App\Processor;

class Payment extends Processor
{

    public function post()
    {
        $this->container->logger->info('New payment', ['data' => $this->getProperties()]);

        // Robokassa
        if ($id = (int)$this->getProperty('InvId')) {
            $properties = $this->getProperties();
        } elseif ($resource = $this->getProperty('resource')) {
            // Paypal
            $id = $resource['transactions'][0]['invoice_number'];
            $properties = [
                'paymentId' => $resource['id'],
                'PayerID' => $resource['payer']['payer_info']['payer_id'],
            ];
        }

        /** @var Order $order */
        if ($id && !empty($properties) && $order = Order::query()->find($id)) {
            if ($order->status === 2) {
                return $this->success('OK' . $id);
            }

            if ($order->status === 1) {
                if ($handler = $order->getPaymentHandler($this->container)) {
                    if ($handler->finalize($order, $properties)) {
                        $order->changeStatus(2);

                        return $this->success('Ok');
                    }
                }
            }
        }

        return $this->failure('Error');
    }
}