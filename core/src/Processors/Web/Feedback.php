<?php

namespace App\Processors\Web;

use App\Processor;
use App\Service\Mail;

class Feedback extends Processor {

    public function post() {
        if (!$name = trim($this->getProperty('name'))) {
            return $this->failure('Вы должны указать ваше имя');
        }

        $email = trim($this->getProperty('email'));
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->failure('Неправильный email');
        }

        $phone = trim($this->getProperty('phone'));
        if (!$phone || !preg_match('#^\d{10,14}$#', $phone)) {
            return $this->failure('Вы должны указать свой телефон');
        }

        if (!$message = trim($this->getProperty('message'))) {
            return $this->failure('Вы забыли написать сообщение');
        }

        $mail = new Mail($this->container);
        $body = $this->container->view->fetch($mail->tpls['feedback'], [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
        ]);

        return $mail->send(getenv('SITE_MAIL'), 'Новое сообщение с сайта Krafti.ru', $body, [$email, $name])
            ? $this->success()
            : $this->failure('Не могу отправить письмо, попробуйте позже');
    }

}