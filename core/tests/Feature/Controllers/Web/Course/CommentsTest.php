<?php

namespace App\Tests\Feature\Controllers\Web\Course;

use App\Controllers\Web\Course\Comments as Controller;
use App\Model\Comment as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class CommentsTest extends TestCase
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
        return '/api/web/course/comments';
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->where(['review' => true, 'deleted' => false]);
    }

    protected function makeRequestParams($exists = true) : array
    {
        if ($exists) {
            return [
                'course_id' => 1
            ];
        }

        return [
            'course_id' => PHP_INT_MAX
        ];
    }

    public function testListSuccess()
    {
        $this->markTestSkipped('Контроллер не поддерживает списки');
    }
}
