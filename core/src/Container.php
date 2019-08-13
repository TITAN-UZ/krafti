<?php

namespace App;

use App\Model\User;
use App\Service\Logger;
use Firebase\JWT\JWT;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Dotenv\Dotenv;

if (!defined('BASE_DIR')) {
    define('BASE_DIR', dirname(dirname(__DIR__)));
}

/**
 * Class Container
 *
 * @property-read \Fenom $view
 * @property-read \Monolog\Logger logger
 * @property-read \Tuupola\Middleware\JwtAuthentication jwt
 * @property-read \Illuminate\Database\Capsule\Manager capsule
 * @property-read \Illuminate\Database\DatabaseManager db
 * @property-read Request $request
 * @property-read Response $response
 * @property-read \App\Service\Mail $mail
 * @property-read \Vimeo\Vimeo $vimeo
 */
class Container extends \Slim\Container
{

    /** @var User $user */
    public $user = null;
    /** @var array $user_scopes */
    public $user_scopes = [];


    /**
     * Container constructor.
     */
    function __construct()
    {
        parent::__construct();

        try {
            $dotenv = new Dotenv();
            $dotenv->load(BASE_DIR . '/core/.env');
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        $this['view'] = function () {
            $fenom = new \Fenom(new \Fenom\Provider(BASE_DIR . '/core/templates/'));
            $fenom->setCompileDir(BASE_DIR . '/tmp/');
            $fenom->setOptions([
                'disable_native_funcs' => true,
                'disable_cache' => false,
                'force_compile' => false,
                'auto_reload' => true,
                'auto_escape' => true,
                'force_verify' => true,
            ]);

            return $fenom;
        };

        $this['capsule'] = function () {
            $capsule = new \Illuminate\Database\Capsule\Manager;
            $capsule->addConnection([
                'driver' => getenv('DB_DRIVER'),
                'host' => getenv('DB_HOST'),
                'port' => getenv('DB_PORT'),
                'prefix' => getenv('DB_PREFIX'),
                'database' => getenv('DB_DATABASE'),
                'username' => getenv('DB_USERNAME'),
                'password' => getenv('DB_PASSWORD'),
                'charset' => getenv('DB_CHARSET'),
                'collation' => getenv('DB_COLLATION'),
            ]);
            $capsule->bootEloquent();

            return $capsule;
        };
        $this->capsule->setAsGlobal();

        $this['db'] = function () {
            return $this->capsule->getDatabaseManager();
        };

        $this['logger'] = function () {
            $logger = new \Monolog\Logger('logger');
            if (PHP_SAPI == 'cli') {
                $handler = new \Monolog\Handler\EchoHandler(\Monolog\Logger::INFO);
                $handler->setFormatter(new \Monolog\Formatter\LineFormatter(null, null, false, true));
            } else {
                $handler = new Service\Logger(\Monolog\Logger::ERROR);
            }
            $logger->pushHandler($handler);

            return $logger;
        };

        $this['jwt'] = function () {
            $container = $this;

            return new \Tuupola\Middleware\JwtAuthentication([
                'path' => ['/api'],
                'ignore' => [
                    '/api/security/',
                    '/api/web/'
                ],
                'secure' => false, // Dev
                //'logger' => $this->logger,
                'secret' => getenv('JWT_SECRET'),
                'error' => function () use ($container) {
                    return (new Processor($container))->failure('Требуется авторизация', 401);
                },
                'before' => function (Request $request) use ($container) {
                    $container->user = User::query()->where([
                        'id' => $request->getAttribute('token')['id'],
                        'active' => true,
                    ])->first();
                    if ($container->user) {
                        $container->user_scopes = $container->user->role->scope;
                    }
                },
                'after' => function (Response $response) use ($container) {
                    return !$container->user
                        ? (new Processor($container))->failure('Требуется авторизация', 401)
                        : $response;
                },
            ]);
        };

        $this['vimeo'] = function () {
            return new \Vimeo\Vimeo(getenv('VIMEO_ID'), getenv('VIMEO_SECRET'), getenv('VIMEO_TOKEN'));
        };

        $this['mail'] = function () {
            return new \App\Service\Mail($this);
        };
    }


    /**
     * @param $id
     *
     * @return string
     */
    public function makeToken($id)
    {
        $data = [
            'id' => $id,
            'iat' => time(),
            'exp' => time() + (24 * 60 * 60),
            //'exp' => time() + 10,
        ];

        return JWT::encode($data, getenv('JWT_SECRET'));
    }

}
