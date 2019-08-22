<?php

namespace App\Processors\User;

use App\Model\Course;
use App\Model\Order;
use App\Service\Payment\Robokassa;

class Payment extends \App\Processor
{

    public function post()
    {
        /** @var Course $course */
        if (!$course_id = (int)$this->getProperty('course_id')) {
            return $this->failure('Вы должны указать id покпаемого курса');
        } elseif (!$course = Course::query()->where(['active' => true])->find($course_id)) {
            return $this->failure('Не могу загрузить указанный курс');
        }

        $service = $this->getProperty('service');
        if (!in_array($service, ['robokassa', 'paypal'])) {
            return $this->failure('Указан неизвестный способ оплаты');
        }

        $period = (int)$this->getProperty('period');
        if (empty($course->price[$period])) {
            return $this->failure('Не указана стоимость курса');
        }

        $key = [
            'course_id' => $course->id,
            'user_id' => $this->container->user->id,
        ];
        /** @var Order $order */
        $order = Order::query()->where($key)->first();
        if ($order) {
            if ($order->status == 2 && $order->paid_till > date('Y-m-d H:i:s')) {
                return $this->failure('Вы уже оплатили этот курс!');
            }
        } else {
            $order = new Order($key);
        }
        $order->service = $service;
        $order->status = 1; // New
        $order->period = $period;
        $order->paid_at = null;
        $order->paid_till = null;
        $order->cost = $course->price[$period];
        $order->save();

        $link = '';
        switch ($service) {
            case 'robokassa':
                $link = (new Robokassa($this->container))->getPaymentLink($order);
                break;
            //case'paypal':
            //    break;
        }

        return $this->success([
            'redirect' => $link,
        ]);
    }

}