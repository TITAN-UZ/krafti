<?php

namespace App\Processors\Web;

use App\Model\Subscriber;
use App\Processor;

class Subscribe extends Processor
{

    public function put()
    {
        $email = trim($this->getProperty('email'));
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->failure('Неправильный email');
        }

        if (!Subscriber::query()->find($email)) {
            $subscriber = new Subscriber([
                'email' => $email,
                'user_id' => $this->container->user
                    ? $this->container->user->id
                    : null,
            ]);
            $subscriber->save();

            if ($this->container->user) {
                $this->container->user->makeTransaction(getenv('COINS_SUBSCRIBE'), 'subscribe');
            }
        }

        return $this->success();
    }

}