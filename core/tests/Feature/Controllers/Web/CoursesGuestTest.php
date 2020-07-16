<?php

namespace App\Tests\Feature\Controllers\Web;

use App\Controllers\Web\Courses as Controller;
use App\Model\Course as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class CoursesGuestTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;

    protected function getController(): string
    {
        return Controller::class;
    }

    protected function getUri(): string
    {
        return '/api/web/courses';
    }

    protected function modelWhere(Builder $builder): Builder
    {
        return $builder->where(['active' => true]);
    }
}
