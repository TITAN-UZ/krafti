<?php

namespace App\Processors\User;

use App\GetProcessor;
use Illuminate\Database\Eloquent\Builder;
use Slim\Http\Response;

class Order extends GetProcessor
{
    protected $class = 'App\Model\Order';


    /**
     * @return Response
     */
    public function post()
    {
        /** @var \App\Model\Order $order */
        if ($order = $this->container->user->orders()->find((int)$this->getProperty('InvId'))) {
            if ($order->status === 2) {
                return $this->success([
                    'id' => $order->course->id,
                ]);
            }

            if ($order->status === 1) {
                if ($handler = $order->getPaymentHandler($this->container)) {
                    $res = $handler->finalize($order, $this->getProperties());
                    if ($res === null) {
                        return $this->success('Processing...', 204);
                    } elseif ($res === true) {
                        $order->changeStatus(2);

                        return $this->success([
                            'id' => $order->course->id,
                        ]);
                    }
                } else {
                    return $this->failure('Неизвестный способ оплаты');
                }
            }
        }

        return $this->failure('Не могу проверить платёж');
    }


    /**
     * @param Builder $c
     *
     * @return mixed
     */
    public function beforeGet($c)
    {
        return $this->beforeCount($c);
    }


    /**
     * @param Builder $c
     *
     * @return mixed
     */
    protected function beforeCount($c)
    {
        $c->where(['user_id' => $this->container->user->id]);

        return $c;
    }


    /**
     * @param \App\Model\Order $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $data = [
            'id' => $object->id,
            'course_id' => $object->course_id,
            'status' => $object->status,
            'cost' => $object->cost,
            'paid_till' => $object->paid_till
                ? $object->paid_till->toIso8601String()
                : null,
            'period' => $object->period,
        ];

        return $data;
    }

}