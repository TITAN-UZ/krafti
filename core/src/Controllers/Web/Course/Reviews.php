<?php

namespace App\Controllers\Web\Course;

use App\Model\Course;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ResponseInterface;

class Reviews extends \App\Controllers\Web\Reviews
{
    public function get(): ResponseInterface
    {
        if (!Course::query()->where('id', (int)$this->getProperty('course_id'))->count()) {
            return $this->failure('Не могу загрузить курс', 404);
        }

        return parent::get();
    }

    public function beforeCount(Builder $c): Builder
    {
        $c->whereHas('lesson', function (Builder $q) {
            $q->where('course_id', '=', (int)$this->getProperty('course_id'));
        });

        return parent::beforeCount($c);
    }
}
