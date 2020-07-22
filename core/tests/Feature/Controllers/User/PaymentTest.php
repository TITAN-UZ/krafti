<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Payment as Controller;
use App\Model\Course;
use App\Model\Promo;
use App\Model\User;
use App\Tests\Feature\Controllers\RequestStatusTrait;
use App\Tests\TestCase;

class PaymentTest extends TestCase
{
    use RequestStatusTrait;

    protected function getUri(): string
    {
        return '/api/user/payment';
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

    public function testGetSuccess(): void
    {
        $this->getUser()->orders()->where('course_id', '=', 1)->delete();

        /** @var Promo $promo */
        $promo = Promo::query()->firstOrFail();

        $data = [
            'course_id' => 1,
            'code' => $promo->code,
        ];

        $request = $this->createRequest('GET', $this->getUri(), $data)
            ->withAttribute('user', $this->getUser());

        $response = $this->app->handle($request);

        self::assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');
        self::assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    public function testPostRobokassaSuccess(): void
    {
        $this->getUser()->orders()->where('course_id', '=', 1)->delete();

        $data = [
            'course_id' => 1,
            'service' => 'robokassa',
            'period' => 12,
        ];

        $request = $this->createRequest('POST', $this->getUri(), $data)
            ->withAttribute('user', $this->getUser());

        $response = $this->app->handle($request);

        self::assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');
        self::assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    public function testPostPayPalSuccess(): void
    {
        $this->getUser()->orders()->where('course_id', '=', 1)->delete();

        $data = [
            'course_id' => 1,
            'service' => 'paypal',
            'period' => 12,
        ];

        $request = $this->createRequest('POST', $this->getUri(), $data)
            ->withAttribute('user', $this->getUser());

        $response = $this->app->handle($request);

        self::assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');
        self::assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    public function testPostAccountSuccess(): void
    {
        $user = $this->getUser();
        $user->account += getenv('COINS_BUY_BONUS');
        $user->save();

        $course = Course::query()->whereHas('bonus')->firstOrFail();
        $user->orders()->where('course_id', $course->getKey())->delete();

        $data = [
            'course_id' => $course->getKey(),
            'service' => 'account',
            'period' => 12,
        ];

        $request = $this->createRequest('POST', $this->getUri(), $data)
            ->withAttribute('user', $user);

        $response = $this->app->handle($request);

        self::assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');
        self::assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    protected function getUser(): User
    {
        if ($this->userModel === null) {
            $this->userModel = User::query()->where('role_id', 3)->firstOrFail();
        }

        return $this->userModel;
    }
}
