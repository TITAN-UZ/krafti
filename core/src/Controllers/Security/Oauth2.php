<?php

namespace App\Controllers\Security;

// use App\Model\File;
use App\Model\User;
use App\Model\UserOauth;
use App\Service\Logger;
use Hybridauth\Adapter\OAuth2 as Provider;
use Hybridauth\User\Profile;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Vesp\Controllers\Controller;
use Vesp\Helpers\Jwt;
use Vesp\Services\Eloquent;

// use Intervention\Image\ImageManager;

class Oauth2 extends Controller
{
    /** @var Logger $logger */
    protected $logger;

    /** @var User $user */
    protected $user;

    public function __construct(Eloquent $eloquent)
    {
        parent::__construct($eloquent);
        $this->logger = new Logger();
    }

    /**
     * @return ResponseInterface;
     */
    public function get()
    {
        $provider = strtolower(@$this->getProperty('provider'));
        if (!in_array($provider, ['instagram', 'vkontakte'])) {
            return $this->failure([
                'error' => 'Неизвестный провайдер авторизации',
            ]);
        }

        $uri = $this->request->getUri();
        $params = [
            //'provider' => $provider,
        ];
        if ($promo = (string)$this->getProperty('promo')) {
            $params['promo'] = $promo;
        }
        $config = [
            'callback' => $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath() . '?' . http_build_query($params),
            'keys' => [
                'id' => getenv('OAUTH2_' . strtoupper($provider) . '_ID'),
                'secret' => getenv('OAUTH2_' . strtoupper($provider) . '_SECRET'),
            ],
        ];

        try {
            $class = '\Hybridauth\Provider\\' . ucfirst($provider);
            /** @var Provider $service */
            $service = new $class($config);
            $service->authenticate();
            /** @var Profile $profile */
            $profile = $service->getUserProfile();
            $service->disconnect();

            // Add profile to account
            if ($this->user) {
                if (!$oauth = $this->user->oauths()->where(['provider' => $provider])->first()) {
                    $oauth = new UserOauth([
                        'user_id' => $this->user->id,
                        'provider' => $provider,
                    ]);
                }
                $oauth->fill(json_decode(json_encode($profile), true));
                $oauth->save();

                if ($provider == 'instagram' && !$this->user->instagram) {
                    $tmp = explode('/', $profile->profileURL);
                    $this->user->instagram = array_pop($tmp);
                    $this->user->save();
                }

                return $this->success([
                    'success' => true,
                ]);
            }

            /** @var UserOauth $oauth */
            if (
                $oauth = UserOauth::query()->where([
                'provider' => $provider,
                'identifier' => $profile->identifier,
                ])->first()
            ) {
                $user = $oauth->user;
            } else {
                $oauth = new UserOauth([
                    'provider' => $provider,
                ]);
                /** @var User $user */
                if (!empty($profile->email) && $user = User::query()->where(['email' => $profile->email])->first()) {
                    // Link account to existing user
                    $oauth->user_id = $user->id;
                } else {
                    return $this->failure('Регистрация через соцсети отключена');
                    /*
                    // Register new user
                    $processor = new Register();
                    $processor->setProperties([
                        'password' => bin2hex(openssl_random_pseudo_bytes(8)),
                        'email' => $profile->email,
                        'fullname' => !empty($profile->firstName) && !empty($profile->lastName)
                            ? $profile->firstName . ' ' . $profile->lastName
                            : $profile->displayName,
                        'promo' => $promo,
                    ]);
                    if ($provider == 'instagram') {
                        $tmp = explode('/', $profile->profileURL);
                        $processor->setProperty('instagram', array_pop($tmp));
                    }

                    $response = $processor->post();
                    if ($response->getStatusCode() !== 200) {
                        return $response;
                    }

                    // @var User $user
                    if ($user_id = json_decode($response->getBody()->__toString())->id) {
                        $user = User::query()->find($user_id);
                        $oauth->user_id = $user_id;
                    } else {
                        $this->logger->error('Could not get saved user for oAuth',
                            ['data' => $oauth->toArray()]);

                        return $this->failure([
                            'error' => 'Не могу получить созданного пользователя',
                        ]);
                    }

                    // Import photo from remote service
                    if ($profile->photoURL && $image = file_get_contents($profile->photoURL)) {
                        $manager = new ImageManager(['driver' => 'imagick']);
                        $res = $manager->make($image);
                        if ($data = $res->encode('data-url')) {
                            $file = new File();
                            if ($file->uploadFile($data)) {
                                $user->photo_id = $file->id;
                                $user->save();
                            }
                        }
                    }
                    */
                }

                $oauth->fill(json_decode(json_encode($profile), true));
                if (!$oauth->save()) {
                    $this->logger->error('Could not save oAuth', ['data' => $oauth->toArray()]);

                    return $this->failure([
                        'error' => 'Не могу сохранить данные соцсети',
                    ]);
                }
            }

            if (!$user->active) {
                return $this->failure([
                    'error' => 'Вход невозможен - пользователь заблокирован',
                ]);
            }

            return $this->success([
                'token' => Jwt::makeToken($user->id),
            ]);
        } catch (Throwable $e) {
            $message = $e->getMessage();
            if (stripos($message, 'denied')) {
                $message = 'Вы отказались давать доступ к своему профилю';
            }

            return $this->failure([
                'error' => $message,
            ]);
        }
    }

    /**
     * @return ResponseInterface;
     */
    public function delete()
    {
        $provider = strtolower(@$this->getProperty('provider'));
        if (!in_array($provider, ['instagram', 'vkontakte'])) {
            return $this->failure('Неизвестный провайдер авторизации');
        }

        if (!$this->user) {
            return $this->failure('Вы должны быть авторизованы для выполнения этой операции');
        }
        if (!$this->user->email && $this->user->oauths()->count() < 2) {
            return $this->failure('Вы не можете удалить свой единственный способ входа на сайт');
        }

        /** @var UserOauth $oauth */
        if ($oauth = $this->user->oauths()->where('provider', $provider)->first()) {
            try {
                $oauth->delete();
            } catch (Throwable $e) {
                return $this->failure($e->getMessage());
            }
        }

        return $this->success();
    }
}
