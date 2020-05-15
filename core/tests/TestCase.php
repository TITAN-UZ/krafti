<?php

namespace App\Tests;

use DI\Bridge\Slim\Bridge;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * @codeCoverageIgnore
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var App $app */
    protected $app;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $app = Bridge::create();
        $app->addBodyParsingMiddleware();
        $app->addRoutingMiddleware();
        $app->add(\App\Middlewares\Auth::class);
        $app->add(new \RKA\Middleware\IpAddress());

        $this->app = $app;

        $this->app->getContainer()->get(\Vesp\Services\Eloquent::class);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $params
     * @return ServerRequestInterface
     */
    public function createRequest($method, $uri, $params = []): RequestInterface
    {
        $method = strtoupper($method);
        $request = (new ServerRequestFactory())->createServerRequest($method, $uri);
        if ($method === 'GET') {
            $request = $request->withQueryParams($params);
        } else {
            $request = $request->withParsedBody($params);
        }

        return $request;
    }
}
