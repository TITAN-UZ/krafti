<?php

namespace App\Controllers\Admin;

use App\Model\Comment;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class Comments extends ModelController
{
    protected $model = Comment::class;
    protected $scope = 'comments';

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where('text', 'LIKE', "%{$query}%");
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount(Builder $c): Builder
    {
        $c->with('lesson:id,title,course_id', 'lesson.course:id,title');
        $c->with('user:id,fullname,photo_id', 'user.photo:id,updated_at');
        $c->orderBy('created_at', 'desc');

        return $c;
    }
}
