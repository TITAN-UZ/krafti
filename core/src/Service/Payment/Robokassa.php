<?php

namespace App\Service\Payment;

use App\Model\Order;

class Robokassa extends Payment
{
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
            'IncCurrLabel' => '',
            'Culture' => 'ru',
            'Receipt' => json_encode([
                'sno' => 'usn_income',
                'items' => [
                    'name' => $order->course->title,
                    'quantity' => 1,
                    'sum' => $order->cost,
                    'payment_method' => 'full_payment',
                    'payment_object' => 'payment',
                    'tax' => 'none',
                ],
            ]),
        ];
        $request['SignatureValue'] = sha1(implode(
            ':',
            [$login, $order->cost, $order->id, $request['Receipt'], $pass1]
        ));
        if (getenv('ROBOKASSA_TEST')) {
            $request['isTest'] = 1;
        }

        return getenv('ROBOKASSA_URL') . '?' . http_build_query($request);
    }

    /**
     * https://docs.robokassa.ru/#1222
     * https://auth.robokassa.ru/Merchant/WebService/Service.asmx/OpState
     * OutSum=3990.00&InvId=4&SignatureValue=f8fbb4e66a79248be714073d71ee40af61a8e97f&IsTest=1&Culture=en
     *
     * @param Order $order
     * @param array $params
     *
     * @return bool
     */
    public function finalize(Order $order, array $params)
    {
        $pass2 = !empty($params['IsTest'])
            ? getenv('ROBOKASSA_TEST_PASS_2')
            : getenv('ROBOKASSA_PASS_2');
        $my_crc = sha1(implode(':', [$params['OutSum'], $order->id, $pass2]));

        if (strtoupper($params['SignatureValue']) !== strtoupper($my_crc)) {
            $this->logger->error('Robokassa signature error', [
                'data' => [
                    'order' => $order->toArray(),
                    'params' => $params,
                    'sig' => [
                        'my' => $my_crc,
                        'their' => sha1(implode(':', [$params['OutSum'], $params['InvId'], $pass2])),
                    ],
                ],
            ]);

            return false;
        }

        return true;
    }
}
