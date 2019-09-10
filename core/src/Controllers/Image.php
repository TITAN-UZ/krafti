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

            $image = $manager->cache(function (ImageCache $image) use ($file, $size) {
                /** @noinspection PhpUndefinedMethodInspection */
                $image->make($file->getFile())
                    ->resize($size[0], $size[1], function (Constraint $constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->interlace()
                    ->encode('jpg', 90);
            }, $this::cache_time);
            $response->write($image);

            return $response->withHeader('Content-Type', 'image/jpg');
        }

        return $response->withStatus(404);
    }
}