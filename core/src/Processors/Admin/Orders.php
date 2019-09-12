<?php

namespace App\Processors\Admin;

use App\Model\Order;
use Illuminate\Database\Eloquent\Builder;

class Orders extends \App\ObjectProcessor
{

    protected $class = '\App\Model\Order';
    protected $scope = 'orders';
    /** @var Builder $conditions */
    protected $conditions;


    public function get() {
        $get = parent::get();

        $res = json_decode($get->getBody()->__toString(), true);
        if (isset($res['total'])) {
            $res['total_cost'] = $this->conditions
                ->where(['status' => 2])
                ->sum('cost');

            return $this->success($res);
        }

        return $get;
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->with('user:id,fullname,photo_id');
        $c->with('course:id,title,cover_id');
        $c->select('orders.*');

        if ($query = trim($this->getProperty('query', ''))) {
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
            $c->where(['course_id' => $course_id]);
        }
        if ($service = $this->getProperty('service')) {
            $c->where(['service' => $service]);
        }
        $this->conditions = $c;

        if ($status = (int)$this->getProperty('status')) {
            $c->where(['status' => $status]);
        }

        return $c;
    }



    /**
     * @param Order $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = $object->toArray();
        $array['created_at'] = $object->created_at->toIso8601String();
        $array['updated_at'] = $object->updated_at->toIso8601String();
        $array['paid_at'] = $object->paid_at
            ? $object->paid_at->toIso8601String()
            : null;
        $array['paid_till'] = $object->paid_till
            ? $object->paid_till->toIso8601String()
            : null;

        return $array;
    }


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
            $record->changeStatus($status);
        }

        return $this->get();
    }


    public function put()
    {
        return $this->failure('Заказы создают пользователи');
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