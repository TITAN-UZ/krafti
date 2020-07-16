<?php

namespace App\Controllers\User;

use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelGetController;

class Order extends ModelGetController
{
    protected $model = \App\Model\Order::class;

    /** @var User $user */
    protected $user;

    public function post(): ResponseInterface
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
                    }

                    if ($res === true) {
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

    public function beforeGet(Builder $c): Builder
    {
        return $this->beforeCount($c);
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('user_id', $this->user->id);

        return $c;
    }

    /**
     * @param \App\Model\Order|Model $object
     *
     * @return array
     */
    public function prepareRow(Model $object): array
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
