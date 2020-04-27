<?php

namespace App\Processors\Web\Course;

use App\Processors\Web\Courses;
use Illuminate\Database\Eloquent\Builder;

class Similar extends Courses
{

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->where('id', '!=', (int)$this->getProperty('course_id'));

        return parent::beforeCount($c);
    }
}