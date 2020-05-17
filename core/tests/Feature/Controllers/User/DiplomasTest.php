<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Diplomas as Controller;
use App\Model\Diploma as Model;
use App\Model\User;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class DiplomasTest extends TestCase
{
    use ModelGetControllerTestTrait;

    protected $model = Model::class;

    protected function getController()
    {
        return Controller::class;
    }

    protected function getUri()
    {
        return '/api/user/diplomas';
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->whereNotNull('file_id')
            ->where('user_id', '=', $this->getUser()->getKey());
    }

    protected function getUser()
    {
        if ($this->userModel === null) {
            $this->userModel = User::whereHas('diplomas')->firstOrFail();
        }

        return $this->userModel;
    }
}
