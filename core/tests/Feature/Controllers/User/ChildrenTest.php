<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Children as Controller;
use App\Model\User;
use App\Model\UserChild as Model;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class ChildrenTest extends TestCase
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
        return '/api/user/children';
    }

    protected function getUser()
    {
        if ($this->userModel === null) {
            $this->userModel = User::whereHas('children')->firstOrFail();
        }

        return $this->userModel;
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->where(['user_id' => $this->getUser()->getKey()]);
    }
}
