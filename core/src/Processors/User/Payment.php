<?php

namespace App\Processors\User;

use App\Model\Course;
use App\Model\Order;
use App\Model\Promo;
use App\Processor;
use App\Service\Payment\Paypal;
use App\Service\Payment\Robokassa;

class Payment extends Processor
{
    // Запросы могут слать и гости
    //protected $scope = 'profile';

    public function get()
    {
        /** @var Promo $promo */
        $promo = Promo::query()->where('code', trim($this->getProperty('code')))->first();
        /** @var Course $course */
        $course = Course::query()->find((int)$this->getProperty('course_id'))->first();

        if ($promo && $course) {
            $check = $promo->check($course->id);
            if ($check !== true) {
                return $this->success([
                    'success' => false,
                    'message' => $check,
                ]);
            }

            if ($discount = $course->getDiscount($this->container->user, $promo)) {
                return $this->success([
                    'success' => true,
                    'discount' => $discount,
                ]);
            }
        }

        return $this->success([
            'success' => false,
        ]);
    }


    public function post()
    {
        if (!$this->container->user) {
            return $this->failure('Требуется авторизация', 401);
        }

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


        if ($course->wasBought($this->container->user)) {
            return $this->success('Вы уже оплатили этот курс!');
        }

        $period = (int)$this->getProperty('period');
        if (empty($course->price[$period])) {
            return $this->failure('Не указана стоимость курса');
        }
        $cost = $course->price[$period];

        // Создаём новый заказ
        $key = [
            'course_id' => $course->id,
            'user_id' => $this->container->user->id,
        ];
        /** @var Order $order */
        if (!$order = Order::query()->where($key)->where('status', 1)->first()) {
            $order = new Order($key);
        } else {
            $order->discount = 0;
            $order->promo_id = null;
        }

        // Проверяем возможные скидки
        $code = $this->getProperty('code');
        /** @var Promo $promo */
        if ($code && $promo = Promo::query()->where(['code' => $code])->first()) {
            $check = $promo->check($course->id);
            if ($check !== true) {
                return $this->failure($check);
            }
            if ($discount = $course->getDiscount($this->container->user, $promo)) {
                $order->promo_id = $promo->id;
                $promo->used += 1;
                $promo->save();
            }
        } else {
            $discount = $course->getDiscount($this->container->user);
        }

        if ($discount) {
            $order->discount = $discount['discount'];
            $order->discount_type = $discount['type'];

            $cost -= (substr($discount['discount'], -1) == '%')
                ? $cost * (rtrim($discount['discount'], '%') / 100)
                : $discount['discount'];
            if ($cost < 0) {
                $cost = 0;
            }
        }

        $order->service = $service;
        $order->status = 1; // New
        $order->period = $period;
        $order->paid_at = null;
        $order->paid_till = null;
        $order->cost = $cost;
        $order->save();

        $link = false;
        if (!$order->cost) {
            // Если скидка полностью перекрыла цену - сразу активируем заказ
            $order->changeStatus(2);
            $link = getenv('SITE_URL') . 'courses/' . $course_id;
        } else {
            // Иначе отправляем на оплату
            switch ($service) {
                case 'robokassa':
                    $link = (new Robokassa($this->container))->getPaymentLink($order);
                    break;
                case'paypal':
                    $link = (new Paypal($this->container))->getPaymentLink($order);
                    break;
            }
        }

        return $link
            ? $this->success(['redirect' => $link])
            : $this->failure('Не могу сгенерировать ссылку на оплату');
    }

}