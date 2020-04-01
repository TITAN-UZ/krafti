<?php

namespace App\Processors\Security;

use App\Model\UserToken;
use App\Processor;

class Logout extends Processor
{

    protected $scope = 'profile';


    public function post()
    {
        /** @var UserToken $token */
        if ($token = $this->container->user->tokens()->where(['token' => $this->container->request->getAttribute('token')])->first()) {
            $token->active = false;
            $token->save();
        }

        return $this->success();
    }

}