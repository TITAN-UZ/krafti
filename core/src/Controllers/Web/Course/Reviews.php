<?php

namespace App\Controllers\Web\Course;

use Illuminate\Database\Eloquent\Builder;

class Reviews extends \App\Controllers\Web\Reviews
{

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeCount($c)
    {
        $c->whereHas('lesson', function (Builder $q) {
            $q->where('course_id', '=', (int)$this->getProperty('course_id'));
        });

        return parent::beforeCount($c);
    }
}