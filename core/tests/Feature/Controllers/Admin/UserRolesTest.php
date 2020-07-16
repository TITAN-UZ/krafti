<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\UserRoles as Controller;
use App\Model\UserRole as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class UserRolesTest extends TestCase
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
        return '/api/admin/user-roles';
    }

    protected function getDefaultListQuery()
    {
        return [
            'query' => 'Администратор',
        ];
    }
}
