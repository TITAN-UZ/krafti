<?php

namespace App\Controllers;

use App\Container;
use App\Processor;
use Slim\Http\Request;
use Slim\Http\Response;

class Api
{

    /** @var Container */
    protected $container;


    public function __construct($container)
    {
        $this->container = $container;
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function process($request, $response, $args = [])
    {
        $name = preg_replace_callback('#-(\w)#s', function ($matches) use ($args) {
            return ucfirst($matches[1]);
        }, $args['name']);
        $class = '\App\Processors\\' . implode('\\', array_map('ucfirst', explode('/', $name)));
        if (!class_exists($class)) {
            return (new Processor($this->container))->failure('Запрошен неизвестный метод "' . $args['name'] . '"');
        }

        $this->container->loadUser();
        /** @var Processor $processor */
        $processor = new $class($this->container);

        return $processor->process();
    }
}