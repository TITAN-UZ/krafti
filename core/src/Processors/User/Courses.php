<?php

namespace App\Processors\User;

use Illuminate\Database\Eloquent\Builder;

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
            $c->whereHas('orders', function (Builder $c) {
                $c->where([
                    'user_id' => $this->container->user->id,
                    'status' => 2,
                ]);
                $c->where('paid_till', '>', date('Y-m-d H:i:s'));
            });
        }

        return $c;
    }
}