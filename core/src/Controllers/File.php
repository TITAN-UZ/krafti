<?php

namespace App\Controllers;

use App\Container;
use App\Model\File as FileObject;
use Slim\Http\Request;
use Slim\Http\Response;

class File
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
        /** @var FileObject $file */
        if ($file = FileObject::query()->find($args['id'])) {
            $response->write($file->getFileContent());

            return $response
                ->withHeader('Content-Type', $file->type)
                ->withHeader('Content-Disposition', 'attachment; filename="' . $file->title . '"');
        }

        return $response->withStatus(404);
    }
}