<?php

namespace App\Processors\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class Courses extends \App\Processors\Web\Courses
{
    protected $scope = 'profile';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeGet($c)
    {
        return $this->beforeCount($c);
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeCount($c)
    {
        if ($this->container->user->role_id > 2) {
            $c->join('orders', function (JoinClause $join) {
                $join->on('orders.course_id', '=', 'courses.id')
                    ->where([
                        'orders.user_id' => $this->container->user->id,
                        'orders.status' => 2,
                    ])
                    ->where('orders.paid_till', '>', date('Y-m-d H:i:s'));
            });
            $c->groupBy('courses.id');
            $c->select(['courses.*', 'orders.paid_till as paid_till']);
        }

        return $c;
    }
}