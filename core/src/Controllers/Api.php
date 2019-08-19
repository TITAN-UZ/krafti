<?php

namespace App\Controllers;

use App\Model\User;
use Exception;
use Firebase\JWT\JWT;
use Psr\Log\LogLevel;
use RuntimeException;

class Api
{

    /** @var \App\Container */
    protected $container;


    public function __construct($container)
    {
        $this->container = $container;
    }


    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param array $args
     *
     * @return \Slim\Http\Response
     */
    public function process($request, $response, $args = [])
    {
        $name = preg_replace_callback('#-(\w)#s', function ($matches) use ($args) {
            return ucfirst($matches[1]);
        }, $args['name']);
        $class = '\App\Processors\\' . implode('\\', array_map('ucfirst', explode('/', $name)));
        if (!class_exists($class)) {
            return (new \App\Processor($this->container))->failure('Запрошен неизвестный метод "' . $args['name'] . '"');
        }

        if ($token = $this->getToken($request)) {
            /** @var User $user */
            if ($user = User::query()->where(['active' => true])->find($token->id)) {
                $this->container->user = $user;
                $this->container->user_scopes = $user->role->scope;
            }
        }

        /** @var \App\Processor $processor */
        $processor = new $class($this->container);

        return $processor->process();
    }


    /**
     * @param \Slim\Http\Request $request
     *
     * @return object|null
     */
    protected function getToken($request)
    {
        $pcre = '#Bearer\s+(.*)$#i';
        $token = null;

        $header = $request->getHeaderLine('Authorization');
        if (!empty($header) && preg_match($pcre, $header, $matches)) {
            $token = $matches[1];
        } else {
            $cookieParams = $request->getCookieParams();
            if (isset($cookieParams['auth._token.local'])) {
                $token = preg_match($pcre, $cookieParams['auth._token.local'], $matches)
                    ? $matches[1]
                    : $cookieParams['auth._token.local'];
            };
        }

        if ($token) {
            try {
                return JWT::decode($token, getenv('JWT_SECRET'), ["HS256", "HS512", "HS384"]);
            } catch (Exception $e) {
                echo $e->getMessage();die;
            }
        }

        return null;
    }
}