<?php

namespace App\Processors\User;

use App\Model\Course;
use App\Model\Order;
use App\Model\Promo;
use App\Service\Payment\Paypal;
use App\Service\Payment\Robokassa;

class Payment extends \App\Processor
{

    public function get()
    {
        $code = trim($this->getProperty('code'));

        /** @var Promo $promo */
        if ($code && $promo = Promo::query()->where(['code' => $code])->first()) {
            $check = $promo->check();
            if ($check !== true) {
                return $this->success([
                    'success' => false,
                    'message' => $check,
                ]);
            }

            return $this->success([
                'success' => true,
            ]);
        }

        return $this->success([
            'success' => false,
        ]);
    }


    public function post()
    {
        /** @var Course $course */
        if (!$course_id = (int)$this->getProperty('course_id')) {
            return $this->failure('Вы должны указать id покупаемого курса');
        } elseif (!$course = Course::query()->where(['active' => true])->find($course_id)) {
            return $this->failure('Не могу загрузить указанный курс');
        }

        $service = $this->getProperty('service');
        if ($service == 'account') {
            $price = getenv('COINS_BUY_BONUS');

            if (!$course->bonus) {
                return $this->failure('У этого курса нет бонусного урока');
            } elseif ($this->container->user->account < $price) {
                return $this->failure('Не хватает крафтиков для покупки');
            }

            $this->container->user->makeTransaction($price * -1, 'bonus', ['course_id' => $course->id]);
            $this->container->user->makeProgress($course, null, true);

            return $this->success([
                'lesson_id' => $course->bonus->id,
            ]);
        } elseif (!in_array($service, ['robokassa', 'paypal'])) {
            return $this->failure('Указан неизвестный способ оплаты');
        }

        /** @var Order $order */
        if ($course->wasBought($this->container->user->id)) {
            return $this->success('Вы уже оплатили этот курс!');
        }

        $period = (int)$this->getProperty('period');
        if (empty($course->price[$period])) {
            return $this->failure('Не указана стоимость курса');
        }
        $cost = $course->price[$period];
        $discount = $course->getDiscount($this->container->user->id);

        $key = [
            'course_id' => $course->id,
            'user_id' => $this->container->user->id,
        ];
        if (!$order = Order::query()->where($key)->first()) {
            $order = new Order($key);
        } else {
            $order->discount = 0;
            $order->promo_id = null;
        }

        // Check promo code
        $code = $this->getProperty('code');
        /** @var Promo $promo */
        if ($code && $promo = Promo::query()->where(['code' => $code])->first()) {
            $check = $promo->check();
            if ($check !== true) {
                return $this->failure($check);
            }

            $tmp = $promo->percent
                ? $cost * ($promo->discount / 100)
                : $promo->discount;
            if ($tmp > $discount) {
                $discount = $tmp;
                $order->promo_id = $promo->id;
                $promo->used += 1;
                $promo->save();
            }
        }

        $order->service = $service;
        $order->status = 1; // New
        $order->period = $period;
        $order->paid_at = null;
        $order->paid_till = null;
        $order->cost = $cost - $discount;
        $order->discount = $discount;
        $order->save();

        $link = false;
        switch ($service) {
            case 'robokassa':
                $link = (new Robokassa($this->container))->getPaymentLink($order);
                break;
            case'paypal':
                $link = (new Paypal($this->container))->getPaymentLink($order);
                break;
        }

        return $link
            ? $this->success(['redirect' => $link])
            : $this->failure('Не могу сгенерировать ссылку на оплату');
    }

}