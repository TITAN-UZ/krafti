<?php

namespace App\Controllers\User;

use Illuminate\Database\Eloquent\Builder;

class Courses extends \App\Controllers\Web\Courses
{
    protected $scope = 'profile';

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeGet(Builder $c): Builder
    {
        return $this->beforeCount($c);
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeCount(Builder $c): Builder
    {
        if ($this->user->role_id > 2) {
            $c->whereHas('orders', function (Builder $c) {
                $c->where([
                    'user_id' => $this->user->id,
                    'status' => 2,
                ]);
                $c->where('paid_till', '>', date('Y-m-d H:i:s'));
            });
        }

        return $c;
    }
}
