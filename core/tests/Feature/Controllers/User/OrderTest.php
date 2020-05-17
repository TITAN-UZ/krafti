<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Order as Controller;
use App\Model\Order as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class OrderTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;
    protected $user = true;

    protected function getController()
    {
        return Controller::class;
    }

    protected function getUri()
    {
        return '/api/user/order';
    }
}
