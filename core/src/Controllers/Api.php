<?php

namespace App\Controllers;

use DI\Container;
use Fig\Http\Message\RequestMethodInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Routing\RouteContext;
use Throwable;
use Vesp\Controllers\Controller;

class Api extends Controller
{
    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function process(Request $request, ResponseInterface $response)
    {
        $routeContext = RouteContext::fromRequest($request);
        $this->route = $routeContext->getRoute();
        $this->request = $request;
        $this->response = $response;

        $name = preg_replace_callback(
            '#-(\w)#s',
            static function ($matches) {
                return ucfirst($matches[1]);
            },
            $this->route->getArgument('name')
        );

        /*if (!preg_match('#^(web|admin)/#', $name)) {
            $name = 'admin/' . ltrim($name, '/');
        }*/

        $class = '\App\Controllers\\' . implode('\\', array_map('ucfirst', explode('/', $name)));
        try {
            $container = new Container();
            /** @var Controller $controller */
            $controller = $container->get($class);
            if ($request->getMethod() === RequestMethodInterface::METHOD_DELETE) {
                $request = $request->withParsedBody($request->getQueryParams());
            }

            return $controller->process($request, $response);
        } catch (Throwable $e) {
            return $this->failure('Not Found', 404);
        }
    }
}
