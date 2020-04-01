<?php

namespace App\Controllers;

use App\Container;
use Slim\Http\Response;

class Home
{
    /** @var Container */
    protected $container;


    public function __construct($container)
    {
        $this->container = $container;
    }


    /**
     * @param array $args
     *
     * @return Response
     */
    public function process($args = [])
    {
        $html = file_get_contents(BASE_DIR . '/frontend/dist/200.html');
        $this->container->response->getBody()->write($html);

        return $this->container->response;
    }
}