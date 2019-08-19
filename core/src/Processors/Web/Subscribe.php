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
            $subscriber = new Subscriber(['email' => $email]);
            $subscriber->save();
        }

        return $this->success();
    }

}