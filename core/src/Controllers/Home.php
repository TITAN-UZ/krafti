<?php

namespace App\Controllers;

class Home
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
        $html = file_get_contents(BASE_DIR . '/frontend/dist/200.html');
        $this->container->response->getBody()->write($html);

        return $this->container->response;
    }
}