<?php

namespace App\Tests\Feature\Controllers\Admin\Charts;

use App\Controllers\Admin\Charts\Orders as Controller;
use App\Model\Order as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class OrdersTest extends TestCase
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
        return '/api/admin/charts/orders';
    }

    public function testNotFoundSuccess(): void
    {
        self::markTestSkipped('Контроллер не поддерживает просмотр записей по отдельности');
    }
}
