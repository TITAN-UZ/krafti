<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Progress as Controller;
use App\Model\Lesson;
use App\Model\Order;
use App\Tests\Feature\Controllers\RequestStatusTrait;
use App\Tests\TestCase;

class ProgressTest extends TestCase
{
    use RequestStatusTrait;

    protected function getUri()
    {
        return '/api/user/progress';
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

    public function testSuccess()
    {
        /** @var Lesson $lesson */
        $lesson = Lesson::findOrFail(5);

        /** @var Order $order */
        $order = $lesson->course->orders()->where('status', '>=', 2)
            ->whereDate('paid_till', '>=', date('Y-m-d'))
            ->firstOrFail();

        $user = $order->user;

        $request = $this->createRequest('POST', $this->getUri(), ['lesson_id' => 5])
            ->withAttribute('user', $user);

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');

        $this->assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    public function testPaidEndTillSuccess()
    {
        /** @var Lesson $lesson */
        $lesson = Lesson::findOrFail(5);

        /** @var Order $order */
        $order = $lesson->course->orders()->where('status', '>=', 2)
            ->whereDate('paid_till', '<=', date('Y-m-d'))
            ->firstOrFail();

        $user = $order->user;

        $request = $this->createRequest('POST', $this->getUri(), ['lesson_id' => 5])
            ->withAttribute('user', $user);

        $response = $this->app->handle($request);

        $this->assertEquals(403, $response->getStatusCode(), 'Ожидается ответ 403');

        $this->assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }
}
