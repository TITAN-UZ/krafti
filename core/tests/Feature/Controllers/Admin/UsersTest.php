<?php

namespace App\Tests\Feature\Controllers\Admin;

use App\Controllers\Admin\Users as Controller;
use App\Model\User as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;

class UsersTest extends TestCase
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
        return '/api/admin/users';
    }

    protected function getDefaultListQuery()
    {
        return [
            'query' => '@mail',
            'active' => 1,
            'confirmed' => 1,
            'combo' => 1,
            'role_id' => 2
        ];
    }
}
