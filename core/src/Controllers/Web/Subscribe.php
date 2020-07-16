<?php

namespace App\Controllers\Web;

use App\Model\Subscriber;
use App\Model\User;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Subscribe extends Controller
{
    /** @var User $user */
    protected $user;

    /**
     * @return ResponseInterface
     */
    public function put(): ResponseInterface
    {
        $email = trim($this->getProperty('email'));
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->failure('Неправильный email');
        }

        if (!Subscriber::query()->find($email)) {
            $subscriber = new Subscriber([
                'email' => $email,
                'user_id' => $this->user
                    ? $this->user->id
                    : null,
            ]);
            $subscriber->save();

            if ($this->user) {
                $this->user->makeTransaction(getenv('COINS_SUBSCRIBE'), 'subscribe');
            }
        }

        return $this->success();
    }
}
