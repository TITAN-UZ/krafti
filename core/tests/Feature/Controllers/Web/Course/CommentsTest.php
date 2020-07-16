<?php

namespace App\Tests\Feature\Controllers\Web\Course;

use App\Controllers\Web\Course\Comments as Controller;
use App\Model\Comment as Model;
use App\Model\User;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class CommentsTest extends TestCase
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
        return '/api/web/course/comments';
    }

    protected function modelWhere(Builder $builder): Builder
    {
        return $builder->where(['review' => true, 'deleted' => false]);
    }

    protected function getUser(): ?User
    {
        if ($this->userModel === null) {
            $this->userModel = User::query()
                ->where('role_id', 1)
                ->firstOrFail();
        }

        return $this->userModel;
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

        return [
            'course_id' => $model->lesson->course_id,
            'lesson_id' => $model->lesson->id,
        ];
    }

    public function testPutWithParentSuccess(): void
    {
        $user = $this->getUser();

        $comment = Model::query()
            ->where('lesson_id', $this->getModelRecord()->lesson->id)
            ->where('user_id', '!=', $user->getKey())->firstOrFail();

        $this->putSuccess([
            'text' => 'test message',
            'lesson_id' => $this->getModelRecord()->lesson->id,
            'parent_id' => $comment->getKey(),
        ]);
    }

    public function testPutSuccess(): void
    {
        $this->putSuccess(['text' => 'test message', 'lesson_id' => $this->getModelRecord()->lesson->id]);
    }

    public function testPutFail(): void
    {
        $this->putFail(['text' => 'test message', 'lesson_id' => PHP_INT_MAX], 'Не могу загрузить урок');
    }

    public function testDeleteSuccess(): void
    {
        $response = $this->sendRequest('DELETE', []);

        self::assertEquals(404, $response->getStatusCode(), 'Ожидается ответ 404');
    }
}
