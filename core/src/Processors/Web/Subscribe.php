<?php

namespace App\Processors\Web;

use App\Model\Subscriber;

class Subscribe extends \App\Processor
{

    public function put()
    {
        $email = trim($this->getProperty('email'));
        if (!$email || !preg_match('#.+@.+\..+#i', $email)) {
            return $this->failure('Вы должны указать email');
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