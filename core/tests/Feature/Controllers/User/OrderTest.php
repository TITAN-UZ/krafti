<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Order as Controller;
use App\Model\Order as Model;
use App\Model\User;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class OrderTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;
    protected $user = true;

    protected function getController(): string
    {
        return Controller::class;
    }

    protected function getUri(): string
    {
        return '/api/user/order';
    }

    protected function getUser()
    {
        if ($this->userModel === null) {
            $this->userModel = User::query()->whereHas('orders')->firstOrFail();
        }

        return $this->userModel;
    }

    protected function getModelRecord(): ?\Illuminate\Database\Eloquent\Model
    {
        if ($this->record === null) {
            $user = $this->getUser();
            $this->record = $user->orders()->firstOrFail();
        }

        return $this->record;
    }
}
