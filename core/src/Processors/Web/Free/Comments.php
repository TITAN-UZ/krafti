<?php

namespace App\Processors\Web\Free;

use App\GetProcessor;
use Illuminate\Database\Eloquent\Builder;

class Comments extends GetProcessor
{
    protected $class = 'App\Model\Comment';
    protected $scope = '';

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->where([
            'lesson_id' => (int)$this->getProperty('lesson_id'),
            'deleted' => false,
        ]);
        $c->whereHas('lesson', function (Builder $c) {
            $c->where('free', true);
        });

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->select('id', 'parent_id', 'text', 'created_at', 'user_id');
        $c->with('user:id,fullname,photo_id', 'user.photo:id,updated_at');

        return $c;
    }
}