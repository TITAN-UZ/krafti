<?php

namespace App\Tests\Feature\Controllers\Web\Course;

use App\Controllers\Web\Course\Lessons as Controller;
use App\Model\Course;
use App\Model\Lesson as Model;
use App\Model\User;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class LessonsTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;
    protected $user = true;

    protected function getController(): string
    {
        return Controller::class;
    }

    protected function getUri(): string
    {
        return '/api/web/course/lessons';
    }

    protected function getUser()
    {
        if ($this->userModel === null) {
            /** @var Course $course */
            $course = Course::query()->first();

            $this->userModel = User::query()
                ->whereHas('progresses', static function (Builder $c) use ($course) {
                    $c->where([
                        'course_id' => $course->id,
                        'section' => 0,
                        'rank' => 0,
                    ]);
                })
                ->firstOrFail();
        }

        return $this->userModel;
    }

    protected function makeRequestParams($exists = true): array
    {
        if ($exists) {
            /** @var Course $course */
            $course = Course::query()->first();

            return [
                'course_id' => $course->id,
                'id' => $course->lessons()->first()->id,
            ];
        }

        return ['id' => PHP_INT_MAX];
    }

    public function getDefaultListQuery(): array
    {
        return [
            'course_id' => Course::query()->first()->id,
        ];
    }
}
