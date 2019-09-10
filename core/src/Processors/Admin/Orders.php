<?php

namespace App\Processors\Admin;

use App\Model\Order;
use Illuminate\Database\Eloquent\Builder;

class Orders extends \App\ObjectProcessor
{

    protected $class = '\App\Model\Order';
    protected $scope = 'orders';


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
            });
        }

        if ($status = (int)$this->getProperty('status')) {
            $c->where(['status' => $status]);
        }
        if ($date = $this->getProperty('date')) {
            $c->whereBetween('created_at', $date);
        }
        if ($course_id = $this->getProperty('course_id')) {
            $c->where(['course_id' => $course_id]);
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


    public function delete()
    {
        return $this->failure('Удалять заказы нелья');
    }
}