<?php

namespace App\Service\Payment;

use App\Model\Order;
use App\Service\Logger;

abstract class Payment
{
    /** @var Logger $logger */
    protected $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }


    /**
     * @param Order $order
     *
     * @return bool
     */
    abstract public function getPaymentLink(Order $order);


    /**
     * @param Order $order
     * @param array $params
     *
     * @return bool
     */
    abstract public function finalize(Order $order, array $params);
}