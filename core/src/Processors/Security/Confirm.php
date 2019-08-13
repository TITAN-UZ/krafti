<?php

namespace App\Processors\Security;

use App\Model\User;

class Confirm extends \App\Processor
{

    public function get()
    {
        $user_id = (int)$this->getProperty('user_id');

        $encrypted = $this->getProperty('secret');
        $type = $this->getProperty('type');
        /** @var User $user */
        if ($encrypted && $user = User::query()->find($user_id)) {
            if ($type == 'reset') {
                $secret = getenv('RESET_SECRET');
                $decrypted = openssl_decrypt(base64_decode($encrypted), 'AES-256-CBC', $secret);
                if ($user->resetPassword($decrypted)) {
                    return $this->success([
                        'token' => $this->container->makeToken($user->id)
                    ]);
                }
            } elseif ($type == 'email') {
                $secret = getenv('EMAIL_SECRET');
                $decrypted = openssl_decrypt(base64_decode($encrypted), 'AES-256-CBC', $secret);
                if ($decrypted == $user->email) {
                    $user->confirmed = true;
                    $user->save();

                    return $this->success();
                }
            }

        }

        return $this->failure('Не могу сбросить пароль');
    }

}