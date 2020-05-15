<?php

namespace App\Tests\Feature\Controllers\Web;

use App\Controllers\Web\Authors as Controller;
use App\Model\User as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class AuthorsTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;

    protected function getController()
    {
        return Controller::class;
    }

    protected function getUri()
    {
        return '/api/web/authors';
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->where(['users.active' => true, 'favorite' => true]);
    }
}
