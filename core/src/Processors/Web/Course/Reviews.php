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
        $c->whereHas('lesson', function(Builder $q) {
            $q->where('course_id', '=', (int)$this->getProperty('course_id'));
        });

        return parent::beforeCount($c);
    }
}