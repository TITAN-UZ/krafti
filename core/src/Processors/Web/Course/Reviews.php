<?php

namespace App\Processors\Web\Course;

use Illuminate\Database\Eloquent\Builder;

class Reviews extends \App\Processors\Web\Reviews
{

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeCount($c)
    {

        $c->join('lessons', 'lessons.id', '=', 'comments.lesson_id');
        $c->where(['lessons.course_id' => (int)$this->getProperty('course_id')]);

        return parent::beforeCount($c);
    }
}