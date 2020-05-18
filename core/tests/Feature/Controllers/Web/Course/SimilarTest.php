<?php

namespace App\Tests\Feature\Controllers\Web\Course;

use App\Controllers\Web\Course\Similar as Controller;
use App\Tests\Feature\Controllers\RequestStatusTrait;
use App\Tests\TestCase;

class SimilarTest extends TestCase
{
    use RequestStatusTrait;

    protected function getUri()
    {
        return '/api/web/course/similar';
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
        $request = $this->createRequest('GET', $this->getUri(), ['course_id' => 1]);

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');

        $this->assertJson($response->getBody()->__toString(), 'Ожидается JSON');
    }
}
