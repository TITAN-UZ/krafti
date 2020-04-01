<?php

namespace App\Processors\Admin;

use App\ObjectProcessor;
use Illuminate\Database\Eloquent\Builder;

class Comments extends ObjectProcessor
{
    protected $class = '\App\Model\Comment';
    protected $scope = 'comments';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
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
    protected function afterCount($c)
    {
        $c->with('lesson:id,title,course_id', 'lesson.course:id,title');
        $c->with('user:id,fullname,photo_id', 'user.photo:id,updated_at');
        $c->orderBy('created_at', 'desc');

        return $c;
    }

}