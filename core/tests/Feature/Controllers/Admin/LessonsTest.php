<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\Lessons as Controller;
use App\Model\Lesson as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class LessonsTest extends TestCase
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
        return '/api/admin/lessons';
    }
}
