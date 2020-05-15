<?php

namespace App\Controllers\Security;

use App\Model\User;
use Vesp\Controllers\Controller;
use Vesp\Helpers\Jwt;

class Confirm extends Controller
{

    public function get()
    {
        $type = $this->getProperty('type');
        $user_id = (int)$this->getProperty('user_id');
        $encrypted = $this->getProperty('secret');

        /** @var User $user */
        if ($encrypted && $user = User::query()->find($user_id)) {
            if ($type == 'reset') {
                $secret = getenv('RESET_SECRET');
                $decrypted = openssl_decrypt(base64_decode($encrypted), 'AES-256-CBC', $secret);
                if ($user->resetPassword($decrypted)) {
                    return $this->success([
                        'token' => Jwt::makeToken($user->id),
                    ]);
                }
            } elseif ($type == 'confirm') {
                $secret = getenv('EMAIL_SECRET');
                $decrypted = openssl_decrypt(base64_decode($encrypted), 'AES-256-CBC', $secret);
                if ($decrypted == $user->email) {
                    $user->confirmed = true;
                    $user->save();

                    return $this->success();
                }
            }
        }

        return $this->failure();
    }

}