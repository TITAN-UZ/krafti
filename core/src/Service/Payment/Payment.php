<?php

namespace App\Service\Payment;

use App\Container;
use App\Model\Order;

abstract class Payment
{
    /** @var Container */
    public $container;


    /**
     * Payment constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    /**
     * @param Order $order
     *
     * @return bool
     */
    public function getPaymentLink(Order $order)
    {
        return false;
    }


    /**
     * @param Order $order
     * @param array $params
     *
     * @return bool
     */
    public function finalize(Order $order, array $params)
    {
        return false;
    }
}