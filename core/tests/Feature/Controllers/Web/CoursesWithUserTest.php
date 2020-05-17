<?php

namespace App\Tests\Feature\Controllers\Web;

use App\Controllers\Web\Courses as Controller;
use App\Model\Course as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class CoursesWithUserTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;
    protected $user = true;

    protected function getController()
    {
        return Controller::class;
    }

    protected function getUri()
    {
        return '/api/web/courses';
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->where(['active' => true]);
    }
}
