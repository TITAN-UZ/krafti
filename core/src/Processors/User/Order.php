<?php

namespace App\Processors\User;

use Illuminate\Database\Eloquent\Builder;

class Order extends \App\GetProcessor
{
    protected $class = 'App\Model\Order';


    /**
     * @param Builder $c
     *
     * @return mixed
     */
    public function beforeGet($c)
    {
        $c->where(['user_id' => $this->container->user->id]);

        return $c;
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
            'paid_till' => $object->paid_till,
            'period' => $object->period,
        ];

        return $data;
    }

}