<?php

namespace App\Tests\Feature\Controllers\Web\Course;

use App\Controllers\Web\Course\Reviews as Controller;
use App\Model\Comment as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class ReviewsTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;

    protected function getController(): string
    {
        return Controller::class;
    }

    protected function getUri(): string
    {
        return '/api/web/course/reviews';
    }

    protected function modelWhere(Builder $builder): Builder
    {
        return $builder->where(['review' => true, 'deleted' => false]);
    }

    protected function makeRequestParams($exists = true): array
    {
        if ($exists) {
            $data = $this->getDefaultListQuery();
            $data['id'] = $this->getModelRecord()->id;

            return $data;
        }

        return ['course_id' => PHP_INT_MAX];
    }

    protected function getDefaultListQuery(): array
    {
        /** @var Model $model */
        $model = $this->getModelRecord();

        return ['course_id' => $model->lesson->course_id];
    }
}
