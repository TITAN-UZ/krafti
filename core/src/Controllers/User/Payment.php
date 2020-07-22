<?php

namespace App\Controllers\User;

use App\Model\Course;
use App\Model\Order;
use App\Model\Promo;
use App\Model\User;
use App\Service\Payment\Paypal;
use App\Service\Payment\Robokassa;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Payment extends Controller
{
    // Запросы могут слать и гости
    //protected $scope = 'profile';
    /** @var User $user */
    protected $user;

    public function get(): ResponseInterface
    {
        /** @var Promo $promo */
        $promo = Promo::query()->where('code', trim($this->getProperty('code')))->first();
        /** @var Course $course */
        $course = Course::query()->find((int)$this->getProperty('course_id'));

        if ($promo && $course) {
            $check = $promo->check($course->id);
            if ($check !== true) {
                return $this->success([
                    'success' => false,
                    'message' => $check,
                ]);
            }

            if ($discount = $course->getDiscount($this->user, $promo)) {
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

    public function post(): ResponseInterface
    {
        if (!$this->user) {
            return $this->failure('Требуется авторизация', 401);
        }

        /** @var Course $course */
        if (!$course_id = (int)$this->getProperty('course_id')) {
            return $this->failure('Вы должны указать id покупаемого курса');
        }

        if (!$course = Course::query()->where('active', true)->find($course_id)) {
            return $this->failure('Не могу загрузить указанный курс');
        }

        $service = $this->getProperty('service');
        if ($service === 'account') {
            $price = getenv('COINS_BUY_BONUS');

            if (!$course->bonus) {
                return $this->failure('У этого курса нет бонусного урока');
            }

            if ($this->user->account < $price) {
                return $this->failure('Не хватает крафтиков для покупки');
            }

            $this->user->makeTransaction($price * -1, 'bonus', ['course_id' => $course->id]);
            $this->user->makeProgress($course, 0, 0);

            return $this->success([
                'lesson_id' => $course->bonus->id,
            ]);
        }

        if (!in_array($service, ['robokassa', 'paypal'])) {
            return $this->failure('Указан неизвестный способ оплаты');
        }


        if ($course->wasBought($this->user)) {
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
            'user_id' => $this->user->id,
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
            if ($discount = $course->getDiscount($this->user, $promo)) {
                $order->promo_id = $promo->id;
                $promo->used++;
                $promo->save();
            }
        } else {
            $discount = $course->getDiscount($this->user);
        }

        if ($discount) {
            $order->discount = $discount['discount'];
            $order->discount_type = $discount['type'];

            $cost -= (substr($discount['discount'], -1) === '%')
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
                    $link = (new Robokassa())->getPaymentLink($order);
                    break;
                case 'paypal':
                    $link = (new Paypal())->getPaymentLink($order);
                    break;
            }
        }

        return $link
            ? $this->success(['redirect' => $link])
            : $this->failure('Не могу сгенерировать ссылку на оплату');
    }
}
