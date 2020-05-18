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

    public function testPutWithParentSuccess()
    {
        $user = $this->getUser();

        $comment = Model::where('lesson_id', '=', 6)->where('user_id', '!=', $user->getKey())->firstOrFail();

        $this->putSuccess(['text' => 'test message', 'lesson_id' => 6, 'parent_id' => $comment->getKey()]);
    }

    public function testPutSuccess()
    {
        $this->putSuccess(['text' => 'test message', 'lesson_id' => 6]);
    }

    public function testPutFail()
    {
        $this->putFail(['text' => 'test message'], 'Необходимо добавить валидацию обязательного параметра lesson_id');
    }

    public function testDeleteSuccess()
    {
        $response = $this->sendRequest('DELETE', []);

        $this->assertEquals(404, $response->getStatusCode(), 'Ожидается ответ 404');
        $this->assertJsonStringEqualsJsonString(
            '"Указан несуществующий метод процессора"',
            $response->getBody()->__toString()
        );
    }
}
