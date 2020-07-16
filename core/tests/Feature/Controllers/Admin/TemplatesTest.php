<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\Templates as Controller;
use App\Model\Template as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class TemplatesTest extends TestCase
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
        return '/api/admin/templates';
    }

    protected function getDefaultListQuery()
    {
        return [
            'query' => 'функции',
        ];
    }
}
