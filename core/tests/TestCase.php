<?php

namespace App\Tests;

use App\Middlewares\Auth;
use DI\Bridge\Slim\Bridge;
use Psr\Http\Message\ServerRequestInterface;
use RKA\Middleware\IpAddress;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Vesp\Services\Eloquent;

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
        $app->add(Auth::class);
        $app->add(new IpAddress());

        $this->app = $app;
        $this->app->getContainer()->get(Eloquent::class);
    }

    public function createRequest(string $method, string $uri, array $params = []): ServerRequestInterface
    {
        $method = strtoupper($method);
        $request = (new ServerRequestFactory())->createServerRequest($method, $uri);

        $request = $method === 'GET'
            ? $request->withQueryParams($params)
            : $request->withParsedBody($params);

        return $request;
    }
}
