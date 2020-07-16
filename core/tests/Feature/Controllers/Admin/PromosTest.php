<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\Promos as Controller;
use App\Model\Promo as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class PromosTest extends TestCase
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
        return '/api/admin/promos';
    }

    protected function getDefaultListQuery()
    {
        return [
            'query' => '@gmail',
        ];
    }
}
