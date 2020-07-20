<?php

namespace App\Controllers\Security;

use App\Model\User;
use App\Service\Fenom;
use App\Service\Logger;
use App\Service\Mail;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Reset extends Controller
{
    public const TIME = 5 * 60; // 5 minutes

    public function post(): ResponseInterface
    {
        $email = trim($this->getProperty('email'));
        /** @var User $user */
        if ($user = User::query()->where('email', $email)->first()) {
            if (!$user->active) {
                return $this->failure('Учётная запись отключена');
            }

            if (strtotime($user->reset_at) > (time() - $this::TIME)) {
                return $this->failure('Мы недавно уже отправили вам ссылку. Пожалуйста, попробуйте через 5 минут.');
            }

            do {
                $rand = openssl_random_pseudo_bytes(4, $strong);
            } while (!$rand || !$strong);
            $password = bin2hex($rand);

            $user->tmp_password = $password;
            $user->reset_at = date('Y-m-d H:i:s');
            $user->save();

            $secret = getenv('RESET_SECRET');
            $encrypted = base64_encode(@openssl_encrypt($password, 'AES-256-CBC', $secret));
            $this->sendMail($user, $encrypted);

            return $this->success();
        }

        return $this->failure('Такого email у нас нет');
    }


    protected function sendMail(User $user, string $secret): bool
    {
        $url = getenv('SITE_URL');
        $mail = new Mail();
        $fenom = new Fenom();
        try {
            $data = $user->toArray();
            $data['link'] = "{$url}service/email/reset?user_id={$user->id}&secret={$secret}";
            if ($from = $this->getProperty('from')) {
                $data['link'] .= "&from={$from}";
            }

            $subject = 'Сброс пароля на Krafti.ru';
            $body = $fenom->fetch($mail->tpls['reset'], $data);
        } catch (Exception $e) {
            (new Logger())->error('Could not fetch email template: ' . $e->getMessage());

            return false;
        }

        return $mail->send($user->email, $subject, $body);
    }
}
