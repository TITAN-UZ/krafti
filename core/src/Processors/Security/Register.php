<?php

namespace App\Processors\Security;

use App\Model\User;

class Register extends \App\Processor
{

    public function post()
    {
        $email = trim($this->getProperty('email'));
        if (!$email || !preg_match('#.+@.+\..+#', $email)) {
            return $this->failure('Вы должны указать адрес электронной почты');
        } elseif (User::query()->where(['email' => $email])->count()) {
            return $this->failure('Этот email уже есть у нас в базе. Укажите другой адрес, или сделайте сброс пароля.');
        }
        if (!$fullname = trim($this->getProperty('fullname'))) {
            return $this->failure('Вы должны указать своё имя');
        }
        if (!$password = trim($this->getProperty('password'))) {
            return $this->failure('Вы должны указать свой пароль');
        } elseif (strlen($password) < 6) {
            return $this->failure('Пароль должен быть не менее 6 символов');
        }

        /** @var User $user */
        $user = new User([
            'email' => $email,
            'fullname' => $fullname,
            'password' => $password,
            'instagram' => trim($this->getProperty('instagram'), ' @'),
            'active' => true,
            'role_id' => 3, // Regular user
        ]);

        if ($user->save()) {
            $secret = getenv('EMAIL_SECRET');
            $encrypted = base64_encode(openssl_encrypt($email, 'AES-256-CBC', $secret));
            $this->sendMail($user, $encrypted);

            return $this->success();
        }

        return $this->failure('Неизвестная ошибка');
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
            $data['link'] = "{$url}service/confirm/email.{$user->id}.{$secret}";

            $subject = 'Вы успешно зарегистрировались на Krafti.ru';
            $body = $this->container->view->fetch(
                $mail->tpls['register'],
                $data
            );
        } catch (\Exception $e) {
            $this->container->logger->error('Could not fetch email template: ' . $e->getMessage());

            return false;
        }

        return $mail->send($user->email, $subject, $body);
    }

}