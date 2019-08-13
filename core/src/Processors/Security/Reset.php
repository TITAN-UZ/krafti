<?php

namespace App\Processors\Security;

use App\Model\User;

class Reset extends \App\Processor
{

    const time = 5 * 60; // 5 minutes


    public function post()
    {
        $email = trim($this->getProperty('email'));
        /** @var User $user */
        if ($user = User::query()->where(['email' => $email])->first()) {
            if (!$user->active) {
                return $this->failure('Учётная запись отключена');
            } elseif (strtotime($user->reset_at) > (time() - $this::time)) {
                return $this->failure('Мы недавно уже отправили вам ссылку. Пожалуйста, попробуйте через 5 минут.');
            } else {
                $password = bin2hex(openssl_random_pseudo_bytes(4));

                $user->tmp_password = $password;
                $user->reset_at = date('Y-m-d H:i:s');
                $user->save();

                $secret = getenv('RESET_SECRET');
                $encrypted = base64_encode(openssl_encrypt($password, 'AES-256-CBC', $secret));
                $this->sendMail($user, $encrypted);

                return $this->success();
            }
        }

        return $this->failure('Такого email у нас нет');
    }


    /**
     * @param User $user
     * @param $secret
     *
     * @return bool
     */
    protected function sendMail($user, $secret)
    {
        $url = getenv('SITE_URL');
        $mail = $this->container->mail;
        try {
            $data = $user->toArray();
            $data['link'] = "{$url}service/confirm/reset.{$user->id}.{$secret}";

            $subject = 'Сброс пароля на Krafti.ru';
            $body = $this->container->view->fetch(
                $mail->tpls['reset'],
                $data
            );
        } catch (\Exception $e) {
            $this->container->logger->error('Could not fetch email template: ' . $e->getMessage());

            return false;
        }

        return $mail->send($user->email, $subject, $body);
    }
}