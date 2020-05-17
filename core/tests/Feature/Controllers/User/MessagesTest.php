<?php

namespace App\Tests\Feature\Controllers\User;

use App\Controllers\User\Messages as Controller;
use App\Model\Message as Model;
use App\Model\User;
use App\Tests\Feature\Controllers\ModelGetControllerTestTrait;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class MessagesTest extends TestCase
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
        return '/api/user/messages';
    }

    protected function getUser()
    {
        if ($this->userModel === null) {
            $this->userModel = User::whereHas('messages')->firstOrFail();
        }

        return $this->userModel;
    }

    protected function modelWhere(Builder $builder)
    {
        return $builder->where('user_id', '=', $this->getUser()->getKey());
    }
}
