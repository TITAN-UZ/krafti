<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Progress as Controller;
use App\Model\Lesson;
use App\Model\Order;
use App\Model\User;
use App\Tests\Feature\Controllers\RequestStatusTrait;
use App\Tests\TestCase;

class ProgressTest extends TestCase
{
    use RequestStatusTrait;

    protected function getUri()
    {
        return '/api/user/progress';
    }

    protected function getController(): string
    {
        return Controller::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->any($this->getUri(), [$this->getController(), 'process']);
    }

    public function testSuccess(): void
    {
        $request = $this->createRequest('POST', $this->getUri(), ['lesson_id' => 5])
            ->withAttribute('user', User::query()->whereHas('progresses')->firstOrFail());

        $response = $this->app->handle($request);

        self::assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');
        self::assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    public function testPaidEndTillSuccess(): void
    {
        /** @var Lesson $lesson */
        $lesson = Lesson::query()->findOrFail(5);

        /** @var Order $order */
        $order = $lesson->course->orders()->where('status', '>=', 2)
            ->whereDate('paid_till', '<=', date('Y-m-d'))
            ->firstOrFail();

        $user = $order->user;

        $request = $this->createRequest('POST', $this->getUri(), ['lesson_id' => 5])
            ->withAttribute('user', $user);

        $response = $this->app->handle($request);

        self::assertEquals(403, $response->getStatusCode(), 'Ожидается ответ 403');
        self::assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }
}
