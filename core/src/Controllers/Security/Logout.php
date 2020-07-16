<?php

namespace App\Controllers\Security;

use App\Model\UserToken;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Logout extends Controller
{
    protected $scope = 'profile';

    /**
     * @return ResponseInterface
     */
    public function post(): ResponseInterface
    {
        /** @var UserToken $token */
        $user_token = $token = UserToken::query()
            ->where(['user_id' => $this->user->id, 'token' => $this->request->getAttribute('token')])
            ->first();
        if ($user_token) {
            $token->active = false;
            $token->save();
        }

        return $this->success();
    }
}
