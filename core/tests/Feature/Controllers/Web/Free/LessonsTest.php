<?php

namespace App\Tests\Feature\Controllers\Web\Free;

use App\Controllers\Web\Free\Lessons as Controller;
use App\Model\Lesson as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class LessonsTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;

    protected function getController()
    {
        return Controller::class;
    }

    protected function getUri()
    {
        return '/api/web/free/lessons';
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->where(['active' => true, 'free' => true]);
    }
}
