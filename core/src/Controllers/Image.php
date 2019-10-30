<?php

namespace App\Controllers;

use App\Model\File;
use Intervention\Image\ImageCache;
use Intervention\Image\ImageManager;
use Intervention\Image\Constraint;

class Image
{

    /** @var \App\Container */
    protected $container;
    const cache_time = 10; // minutes


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
        $manager = new ImageManager([
            'driver' => 'imagick',
            'cache' => [
                'path' => BASE_DIR . '/tmp/imagecache',
            ],
        ]);

        /** @var File $file */
        if ($file = File::query()->find($args['id'])) {
            $size = array_map(function ($v) {
                return empty($v) ? null : $v;
            }, explode('x', $args['size']));

            $params = [];
            if (!empty($args['params'])) {
                $params = explode(',', $args['params']);
            }

            $image = $manager->cache(function (ImageCache $image) use ($file, $size, $params) {
                $image->make($file->getFile());
                if (in_array('fit', $params)) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    $image->fit($size[0], $size[1], function (Constraint $constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                } else {
                    /** @noinspection PhpUndefinedMethodInspection */
                    $image->resize($size[0], $size[1], function (Constraint $constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                /** @noinspection PhpUndefinedMethodInspection */
                $image->interlace()->encode(str_replace('image/', '', $file->type), 90);

            }, $this::cache_time);
            $response->write($image);

            return $response
                ->withHeader('Expires', $file->updated_at->addDay()->toRfc822String())
                ->withHeader('Content-Type', $file->type);
        }

        return $response->withStatus(404);
    }
}