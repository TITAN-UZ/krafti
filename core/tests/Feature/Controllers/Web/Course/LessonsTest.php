<?php

namespace App\Tests\Feature\Controllers\Web\Course;

use App\Controllers\Web\Course\Lessons as Controller;
use App\Model\Lesson as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

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
        return '/api/web/course/lessons';
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->where(['active' => true]);
    }

    public function testListSuccess()
    {
        $this->markTestSkipped('Контроллер не поддерживает списки');
    }

    protected function makeRequestParams($exists = true) : array
    {
        if ($exists) {
            $record = $this->getModelRecord();
            return [
                'course_id' => $record->course_id
            ];
        }

        return [
            'course_id' => PHP_INT_MAX
        ];
    }
}
