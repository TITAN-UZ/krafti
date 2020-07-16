<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\Homeworks as Controller;
use App\Model\Homework as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class HomeworksTest extends TestCase
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
        return '/api/admin/homeworks';
    }

    protected function getDefaultListQuery(): array
    {
        return [
            'limit' => 10,
        ];
    }
}
