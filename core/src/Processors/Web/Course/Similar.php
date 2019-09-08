<?php

namespace App\Processors\Web\Course;

use Illuminate\Database\Eloquent\Builder;

class Similar extends \App\Processors\Web\Courses
{

    protected $class = '\App\Model\Course';


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