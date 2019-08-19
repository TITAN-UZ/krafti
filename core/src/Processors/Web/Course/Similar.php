<?php

namespace App\Processors\Web\Course;

use \Illuminate\Database\Eloquent\Builder;

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
        $this->setProperty('limit', 6);
        $c->where('id', '!=', (int)$this->getProperty('course_id'));

        return $c;
    }
}