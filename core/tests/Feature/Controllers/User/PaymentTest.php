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

    protected function getUri()
    {
        return '/api/user/payment';
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

    public function testGetSuccess()
    {
        $this->getUser()->orders()->where('course_id', '=', 1)->delete();

        /** @var Promo $promo */
        $promo = Promo::firstOrFail();

        $data = [
            'course_id' => 1,
            'code' => $promo->code
        ];

        $request = $this->createRequest('GET', $this->getUri(), $data)
            ->withAttribute('user', $this->getUser());

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');

        $this->assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    public function testPostRobokassaSuccess()
    {
        $this->getUser()->orders()->where('course_id', '=', 1)->delete();

        $data = [
            'course_id' => 1,
            'service' => 'robokassa',
            'period' => 12
        ];

        $request = $this->createRequest('POST', $this->getUri(), $data)
            ->withAttribute('user', $this->getUser());

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');

        $this->assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    public function testPostPayPalSuccess()
    {
        $this->getUser()->orders()->where('course_id', '=', 1)->delete();

        $data = [
            'course_id' => 1,
            'service' => 'paypal',
            'period' => 12
        ];

        $request = $this->createRequest('POST', $this->getUri(), $data)
            ->withAttribute('user', $this->getUser());

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');

        $this->assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    public function testPostAccountSuccess()
    {
        $bonus = Course::whereHas('bonus')->firstOrFail();
        $this->getUser()->orders()->where('course_id', '=', $bonus->getKey())->delete();

        $data = [
            'course_id' => $bonus->getKey(),
            'service' => 'account',
            'period' => 12
        ];

        $request = $this->createRequest('POST', $this->getUri(), $data)
            ->withAttribute('user', $this->getUser());

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');

        $this->assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    protected function getUser()
    {
        if ($this->userModel === null) {
            $this->userModel = User::where('role_id', '=', 3)->firstOrFail();
        }

        return $this->userModel;
    }
}
