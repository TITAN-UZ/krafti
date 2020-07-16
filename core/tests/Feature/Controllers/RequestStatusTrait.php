<?php

namespace App\Tests\Feature\Controllers;

use App\Model\User;
use Psr\Http\Message\RequestInterface;

trait RequestStatusTrait
{
    abstract protected function getUri();

    /** @var User|null */
    protected $userModel;

    protected function getUser(): ?User
    {
        if (!property_exists($this, 'user') || $this->user !== true) {
            return null;
        }

        if ($this->userModel === null) {
            $this->userModel = User::query()->firstOrFail();
        }

        return $this->userModel;
    }

    private function postFail(array $data, string $error)
    {
        $this->requestFail('POST', $data, $error);
    }

    private function putFail(array $data, string $error)
    {
        $this->requestFail('PUT', $data, $error);
    }

    private function postSuccess(array $data, string $message = null)
    {
        $this->requestSuccess('POST', $data, $message);
    }

    private function putSuccess(array $data, string $message = null)
    {
        $this->requestSuccess('PUT', $data, $message = null);
    }

    private function getSuccess(array $data, string $message = null)
    {
        $this->requestSuccess('GET', $data, $message);
    }

    private function sendRequest(string $method, array $data)
    {
        /** @var RequestInterface $request */
        $request = $this->createRequest($method, $this->getUri(), $data);
        $user = $this->getUser();
        if ($user !== null) {
            $request = $request->withAttribute('user', $user);
        }

        return $this->app->handle($request);
    }

    private function requestFail(string $method, array $data, string $error)
    {
        $response = $this->sendRequest($method, $data);

        self::assertEquals(422, $response->getStatusCode(), 'Ожидается ответ 422');
        self::assertJsonStringEqualsJsonString('"' . $error . '"', $response->getBody()->__toString());
    }

    private function requestSuccess(string $method, array $data, string $message = null)
    {
        $response = $this->sendRequest($method, $data);

        self::assertEquals(200, $response->getStatusCode(), 'Ожидается ответ 200');
        self::assertJson($response->getBody()->__toString(), 'Ожидается JSON');
        if ($message !== null) {
            self::assertJsonStringEqualsJsonString(
                $message,
                $response->getBody()->__toString()
            );
        }
    }
}
