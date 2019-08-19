<?php

namespace App\Service\Payment;

use App\Container;
use App\Model\Order;

class Robokassa
{

    /** @var Container */
    public $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    /**
     * @param Order $order
     *
     * @return string
     */
    public function getPaymentLink(Order $order)
    {
        $login = getenv('ROBOKASSA_LOGIN');
        $pass1 = getenv('ROBOKASSA_TEST')
            ? getenv('ROBOKASSA_TEST_PASS_1')
            : getenv('ROBOKASSA_PASS_1');

        $request = [
            'url' => getenv('ROBOKASSA_URL'),
            'MrchLogin' => $login,
            'OutSum' => $order->cost,
            'InvId' => $order->id,
            'Desc' => 'Оплата за курс занятий "' . $order->course->title . '"',
            'SignatureValue' => sha1(implode(':', [$login, $order->cost, $order->id, $pass1])),
            'IncCurrLabel' => '',
            'Culture' => 'RU',
        ];
        if (getenv('ROBOKASSA_TEST')) {
            $request['isTest'] = 1;
        }

        return getenv('ROBOKASSA_URL') . '?' . http_build_query($request);
    }


    /**
     * @param array $data
     *
     * @return bool
     */
    public function checkCrc(array $data)
    {
        $pass2 = !empty($data['IsTest'])
            ? getenv('ROBOKASSA_TEST_PASS_2')
            : getenv('ROBOKASSA_PASS_2');
        $crc = $data['SignatureValue'];
        $my_crc = sha1(implode(':', [$data['OutSum'], $data['InvId'], $pass2]));

        return strtoupper($crc) === strtoupper($my_crc);
    }
}