<?php

namespace App\Processors\Security;

use App\Model\Traits\UserValidate;
use App\Model\User;

class Register extends \App\Processor
{

    use UserValidate;


    public function post()
    {
        if (!$password = trim($this->getProperty('password'))) {
            return $this->failure('Вы должны указать свой пароль');
        } elseif (strlen($password) < 6) {
            return $this->failure('Пароль должен быть не менее 6 символов');
        }

        /** @var User $user */
        $user = new User([
            'email' => trim($this->getProperty('email')),
            'fullname' => trim($this->getProperty('fullname')),
            'password' => trim($this->getProperty('password')),
            'instagram' => trim($this->getProperty('instagram'), ' @'),
            'active' => true,
            'role_id' => 3, // Regular user
        ]);

        if ($promo = trim($this->getProperty('promo'))) {
            /** @var User $referrer */
            if (!$referrer = User::query()->where(['promo' => $promo, 'active' => true])->first()) {
                return $this->failure('Указан недействительный промокод');
            } else {
                $user->referrer_id = $referrer->id;
            }
        }

        $validate = $this->validate($user);
        if ($validate !== true) {
            return $this->failure($validate);
        }

        if ($user->save()) {
            if ($user->email) {
                $secret = getenv('EMAIL_SECRET');
                $encrypted = base64_encode(openssl_encrypt($user->email, 'AES-256-CBC', $secret));
                $this->sendMail($user, $encrypted);
            }
            $this->sendMessage($user);

            return $this->success([
                'id' => $user->id,
            ]);
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
            $data['link'] = "{$url}service/email/confirm?user_id={$user->id}&secret={$secret}";

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


    /**
     * @param User $user
     */
    protected function sendMessage($user)
    {
        $user->sendMessage('Приветствуем в онлайн-академии творчества KRAFTi! С нами вы всей семьёй сможете самостоятельно создавать домашние шедевры. 
Благодарим вас за приобретение детского курса по рисованию и желаем красивых работ и вдохновения!

Спасибо, что поделились своей электронной почтой! Теперь мы сможем сообщать вам о наших новых курсах, акциях и новостях.  Вместе с этим письмом мы дарим вам крафтики, которые вы сможете использовать для приобретения бонусов.
Кстати, крафтики можно получить не только за регистрацию, но и за заполнение палитры прогресса. Если к нам придут ваши друзья, мы отблагодарим их скидкой на курс, а вас — нашими крафтиками.
Если вы тоже захотите поделиться чем-то с нами — пишите на нашу почту, в Instagram или WhatsApp.
В этом письме мы хотим ответить на основные вопросы — это поможет сделать творчество легче и приятнее. 

Сначала мы расскажем, как сориентироваться на нашем сайте.
В первую очередь советуем обратить внимание на личный кабинет. Чем больше данных вы заполните, тем лучше мы сможем познакомиться друг с другом.
В блоке «Наша команда» вы можете узнать больше о нас — тех, кто старается сделать вас ближе к мастерству живописи. 
В блоке «Курсы» мы подробно расписали процесс обучения. Всё просто: после изучения каждого модуля нужно выполнить домашнее задание, после чего открывается следующий модуль. 
А в конце курса, после выполнения всех домашек, вас ждёт полезный бонус.', 'greeting');
    }
}