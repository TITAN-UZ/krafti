<?php

namespace App\Processors\Admin;

use App\Model\Course;
use App\Model\Order;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;

class Orders extends \App\ObjectProcessor
{

    protected $class = '\App\Model\Order';
    protected $scope = 'orders';
    /** @var Builder $conditions */
    protected $conditions;

    /**
     * @return \Slim\Http\Response
     */
    public function patch()
    {
        if (!$id = $this->getProperty($this->primaryKey)) {
            return $this->failure('Вы должны указать id записи');
        }
        /** @var Order $record */
        if (!$record = Order::query()->find($id)) {
            return $this->failure('Не могу найти запись');
        }
        if ($status = $this->getProperty('status')) {
            $record->manual = true;
            $record->changeStatus($status);
        }

        return $this->get();
    }


    /**
     * @return \Slim\Http\Response
     */
    public function put()
    {
        $course_id = $this->getProperty('course_id');
        /** @var Course $course */
        if (!$course_id || !$course = Course::query()->find($course_id)) {
            return $this->failure('Не могу загрузить курс');
        }

        $user_id = $this->getProperty('user_id');
        /** @var User $user */
        if (!$user_id || !$user = User::query()->find($user_id)) {
            return $this->failure('Не могу загрузить пользователя');
        }

        $discount = 0;
        $key = [
            'course_id' => $course->id,
            'user_id' => $user->id,
        ];
        if (Order::query()->where($key)->where(['status' => 1])->count()) {
            return $this->failure('У этого пользователя уже есть неоплаченный заказ');
        } elseif (Order::query()->where($key)->where(['status' => 2])->where('paid_till', '>', date('Y-m-d H:i:s'))->count()) {
            return $this->failure('Этот курс у пользователя уже оплачен');
        }

        if (!$period = $this->getProperty('period')) {
            return $this->failure('Вы должны выбрать период оплаты');
        } elseif (!isset($course->price[$period])) {
            return $this->failure('Указан неверный период оплаты');
        }

        $order = new Order($key);
        $order->service = 'internal';
        $order->status = 1;
        $order->period = $period;
        $order->cost = $course->price[$period] - $discount;
        $order->discount = $discount;
        $order->manual = true;
        $order->save();

        return $this->success($order->toArray());
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->select('orders.*');

        if ($query = trim($this->getProperty('query'))) {
            $c->join('courses', 'courses.id', '=', 'orders.course_id');
            $c->join('users', 'users.id', '=', 'orders.user_id');
            $c->where(function (Builder $c) use ($query) {
                $c->where('courses.title', 'LIKE', "%$query%");
                $c->orWhere('users.fullname', 'LIKE', "%$query%");
                $c->orWhere('users.email', 'LIKE', "%$query%");
            });
        }

        if ($date = $this->getProperty('date')) {
            $c->whereBetween('created_at', $date);
        }
        if ($course_id = $this->getProperty('course_id')) {
            $c->where('course_id', $course_id);
        }
        if ($service = $this->getProperty('service')) {
            $c->where('service', $service);
        }
        $this->conditions = $c;

        if ($status = (int)$this->getProperty('status')) {
            $c->where('status', $status);
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->with('user:id,fullname,photo_id', 'user.photo:id,updated_at');
        $c->with('course:id,title,cover_id');

        return $c;
    }

    /**
     * @param array $array
     * @return array
     */
    public function prepareList(array $array)
    {
        if ($c = $this->conditions) {
            $c->where(['status' => 2]);
            if ($this->getProperty('service') !== 'internal') {
                $c->where('manual', false);
            }
            $array['total_cost'] = (int)$c->sum('cost');
        }

        return $array;
    }


    /**
     * @param Order $record
     *
     * @return bool|string
     */
    public function beforeDelete($record)
    {
        if ($record->status !== 1) {
            return 'Оплаченные заказы удалять нелья';
        }

        return true;
    }
}