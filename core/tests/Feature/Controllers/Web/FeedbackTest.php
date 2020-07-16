<?php

namespace App\Tests\Feature\Controllers\Web;

use App\Controllers\Web\Feedback as Controller;
use App\Tests\Feature\Controllers\RequestStatusTrait;
use App\Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RequestStatusTrait;

    protected function getUri()
    {
        return '/api/web/feedback';
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
        $data = [
            'name' => 'my name',
            'email' => 'test@mail.ru',
            'phone' => '8002000600',
            'message' => 'test message',
        ];

        $request = $this->createRequest('POST', $this->getUri(), $data);

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');

        $this->assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }

    public function testFailName()
    {
        $this->failedField([], 'Вы должны указать ваше имя');
    }

    public function testFailEmail()
    {
        $data = [
            'name' => 'my name',
        ];

        $this->failedField($data, 'Неправильный email');
    }

    public function testFailPhone()
    {
        $data = [
            'name' => 'my name',
            'email' => 'test@mail.ru',
        ];

        $this->failedField($data, 'Вы должны указать свой телефон');
    }

    public function testFailMessage()
    {
        $data = [
            'name' => 'my name',
            'email' => 'test@mail.ru',
            'phone' => '8002000600',
        ];

        $this->failedField($data, 'Вы забыли написать сообщение');
    }

    private function failedField(array $data, string $message)
    {
        $request = $this->createRequest('POST', $this->getUri(), $data);

        $response = $this->app->handle($request);

        $this->assertEquals(422, $response->getStatusCode(), 'Ожидается ответ 422');

        $this->assertJson($response->getBody()->__toString(), 'Ожидается JSON');

        $this->assertJsonStringEqualsJsonString('"' . $message . '"', $response->getBody()->__toString());
    }
}
