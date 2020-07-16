<?php

namespace App\Controllers\Security;

use App\Model\User;
use App\Model\UserToken;
use Psr\Http\Message\ResponseInterface;
use Vesp\Helpers\Jwt;

class Login extends \Vesp\Controllers\Security\Login
{
    public function post(): ResponseInterface
    {
        // Invalidate old tokens
        UserToken::query()
            ->where('active', true)
            ->where('valid_till', '<', date('Y-m-d H:i:s', time()))
            ->update(['active' => false]);

        $email = trim($this->getProperty('email'));
        $password = trim($this->getProperty('password'));

        /** @var User $user */
        if ($user = User::query()->where('email', $email)->first()) {
            if (!$user->active) {
                return $this->failure('Учётная запись отключена');
            }

            if ($user->verifyPassword($password)) {
                $token = Jwt::makeToken($user->id);
                $decoded = Jwt::decodeToken($token);
                $user_token = new UserToken(
                    [
                        'user_id' => $user->id,
                        'token' => $token,
                        'valid_till' => date('Y-m-d H:i:s', $decoded->exp),
                        'ip' => $this->request->getAttribute('ip_address'),
                    ]
                );
                $user_token->save();

                // Limit active tokens
                $max = getenv('JWT_MAX');
                if ($max && UserToken::query()->where(['user_id' => $decoded->id, 'active' => true])->count() > $max) {
                    UserToken::query()
                        ->where(['user_id' => $decoded->id, 'active' => true])
                        ->orderBy('updated_at', 'asc')
                        ->orderBy('created_at', 'asc')
                        ->first()
                        ->update(['active' => false]);
                }

                return $this->success(['token' => $token]);
            }
        }

        return $this->failure('Неправильный логин или пароль');
    }
}
