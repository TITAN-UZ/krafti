<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Courses as Controller;
use App\Model\Course as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class CoursesTest extends TestCase
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
        return '/api/user/courses';
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->where(['active' => true]);
    }
}
