<?php

namespace App\Processors\User;

use App\GetProcessor;
use App\Model\Course;
use App\Model\File;
use App\Model\Homework;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
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
            $c->select('courses.*');
        }

        return $c;
    }

}