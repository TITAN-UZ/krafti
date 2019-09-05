<?php

namespace App\Processors\Security;

use App\Model\User;

class Login extends \App\Processor
{

    public function post()
    {
        $email = trim($this->getProperty('email'));
        $password = trim($this->getProperty('password'));
        /** @var User $user */
        if ($user = User::query()->where(['email' => $email])->first()) {
            if (!$user->active) {
                return $this->failure('Учётная запись отключена');
            } elseif ($user->verifyPassword($password)) {
                return $this->success([
                    'token' => $this->container->makeToken($user->id),
                ]);
            }
        }

        return $this->failure('Неправильный логин или пароль');
    }

}