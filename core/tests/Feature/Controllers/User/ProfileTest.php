<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Profile as Controller;
use App\Model\UserChild;
use App\Tests\Feature\Controllers\RequestStatusTrait;
use App\Tests\TestCase;

class ProfileTest extends TestCase
{
    use RequestStatusTrait;

    protected $user = true;

    protected function getUri()
    {
        return '/api/user/profile';
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
        $this->getSuccess([]);
    }

    public function testPathSuccess()
    {
        $this->requestSuccess(
            'PATCH',
            [
                'email' => 'asd@asd.ru',
                'dob' => '1982-10-24',
                'fullname' => 'test',
                'instagram' => 'test',
                'phone' => '123',
                'company' => 'test',
                'description' => 'test',
                'password' => '123',
                'children' => [
                    ['id' => UserChild::firstOrFail()],
                    [
                        'name' => 'test',
                        'dob' => '1982-10-24',
                        'gender' => 1,
                    ],
                ],
            ]
        );
    }
}
