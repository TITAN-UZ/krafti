<?php

namespace App\Controllers\User;

use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelGetController;

class Order extends ModelGetController
{
    protected $model = Order::class;
    /** @var User $user */
    protected $user;

    /**
     * @return ResponseInterface;
     */
    public function post()
    {
        /** @var \App\Model\Order $order */
        if ($order = $this->user->orders()->find((int)$this->getProperty('InvId'))) {
            if ($order->status === 2) {
                return $this->success([
                    'id' => $order->course->id,
                ]);
            }

            if ($order->status === 1) {
                if ($handler = $order->getPaymentHandler()) {
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
        $c->where(['user_id' => $this->user->id]);

        return $c;
    }


    /**
     * @param \App\Model\Order $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        return [
            'id' => $object->id,
            'course_id' => $object->course_id,
            'status' => $object->status,
            'cost' => $object->cost,
            'paid_till' => $object->paid_till,
            'period' => $object->period,
        ];
    }

}