<?php

namespace App\Controllers\User;

use App\Model\Diploma;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Diplomas extends ModelGetController
{
    protected $scope = 'profile';
    protected $model = Diploma::class;

    /**
     * @param Builder $c
     *
     * @return Builder|mixed
     */
    protected function beforeGet(Builder $c): Builder
    {
        $c = $this->beforeCount($c);

        return $this->afterCount($c);
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount(Builder $c): Builder
    {
        $c->where('user_id', $this->user->id);
        $c->whereNotNull('file_id');

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->select('id', 'file_id', 'course_id', 'child_id');
        $c->with('file:id,updated_at');
        $c->with('course:id,title');
        $c->with('child:id,name,dob');

        return $c;
    }
}
