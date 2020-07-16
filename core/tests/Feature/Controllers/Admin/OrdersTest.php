<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\Orders as Controller;
use App\Model\Order as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class OrdersTest extends TestCase
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
        return '/api/admin/orders';
    }

    protected function getDefaultListQuery()
    {
        return [
            'course_id' => 3,
            'date' => '2019-09-11',
            'query' => '@mail',
        ];
    }
}
