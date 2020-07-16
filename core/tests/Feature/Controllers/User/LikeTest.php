<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Like as Controller;
use App\Model\Lesson;
use App\Tests\Feature\Controllers\RequestStatusTrait;
use App\Tests\TestCase;

class LikeTest extends TestCase
{
    use RequestStatusTrait;

    protected $user = true;

    protected function getUri()
    {
        return '/api/user/like';
    }

    protected function getController()
    {
        return Controller::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->any($this->getUri(), [$this->getController(), 'process']);
    }

    public function testPostSuccess()
    {
        $lesson = Lesson::firstOrFail();

        $this->postSuccess(['lesson_id' => $lesson->getKey()]);
    }

    public function testGetSuccess()
    {
        $this->getSuccess([]);
    }
}
