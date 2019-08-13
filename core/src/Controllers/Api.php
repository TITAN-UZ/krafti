<?php

namespace App\Controllers;

class Api
{

    /** @var \App\Container */
    protected $container;


    public function __construct($container)
    {
        $this->container = $container;
    }


    /**
     * @param array $args
     *
     * @return \Slim\Http\Response
     */
    public function process($args = [])
    {
        $name = preg_replace_callback('#-(\w)#s', function ($matches) use ($args) {
            return ucfirst($matches[1]);
        }, $args['name']);
        $class = '\App\Processors\\' . implode('\\', array_map('ucfirst', explode('/', $name)));
        if (!class_exists($class)) {
            return (new \App\Processor($this->container))->failure('Запрошен неизвестный метод "' . $args['name'] . '"');
        }

        /** @var \App\Processor $processor */
        $processor = new $class($this->container);

        return $processor->process();
    }

}