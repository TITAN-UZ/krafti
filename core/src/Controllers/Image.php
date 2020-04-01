<?php

namespace App\Controllers;

use App\Container;
use App\Model\File;
use Intervention\Image\ImageCache;
use Intervention\Image\ImageManager;
use Intervention\Image\Constraint;
use Slim\Http\Request;
use Slim\Http\Response;
use Throwable;

class Image
{

    /** @var Container */
    protected $container;
    const cache_time = 10; // minutes

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
        $manager = new ImageManager([
            'driver' => 'imagick',
            'cache' => [
                'path' => BASE_DIR . '/tmp/imagecache',
            ],
        ]);
        /** @var File $file */
        if ($file = File::query()->find($args['id'])) {
            try {
                if (!empty($args['size'])) {
                    $size = array_map(function ($v) {
                        return empty($v) ? null : $v;
                    }, explode('x', $args['size']));
                    $params = [];
                    if (!empty($args['params'])) {
                        $params = explode(',', $args['params']);
                    }

                    $image = $manager->cache(function (ImageCache $image) use ($file, $size, $params) {
                            $image->make($file->getFile());

                            if (in_array('auto', $params)) {
                                if ($file->height > $file->width) {
                                    /** @noinspection PhpUndefinedMethodInspection */
                                    $image->resize($size[0], $size[1], function (Constraint $constraint) {
                                        $constraint->aspectRatio();
                                        $constraint->upsize();
                                    });
                                } else {
                                    /** @noinspection PhpUndefinedMethodInspection */
                                    $image->fit($size[0], $size[1], function (Constraint $constraint) {
                                        $constraint->aspectRatio();
                                        $constraint->upsize();
                                    });
                                }
                            } elseif (in_array('fit', $params)) {
                                /** @noinspection PhpUndefinedMethodInspection */
                                $image->fit($size[0], $size[1], function (Constraint $constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });
                            } elseif (in_array('resize', $params)) {
                                /** @noinspection PhpUndefinedMethodInspection */
                                $image->resize($size[0], $size[1], function (Constraint $constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });
                            } else {
                                $types = [
                                    'top-left',
                                    'top',
                                    'top-right',
                                    'left',
                                    'right',
                                    'bottom-left',
                                    'bottom',
                                    'bottom-right'
                                ];
                                if ($intersect = array_intersect($params, $types)) {
                                    $anchor = $intersect[0];
                                } else {
                                    $anchor = 'center';
                                }
                                /** @noinspection PhpUndefinedMethodInspection */
                                $image->resizeCanvas($size[0], $size[1], $anchor);
                            }
                            /** @noinspection PhpUndefinedMethodInspection */
                            $image->interlace()->encode(str_replace('image/', '', $file->type), 90);
                        }, $this::cache_time);
                } else {
                    $image = $manager->cache(function (ImageCache $image) use ($file) {
                        $image->make($file->getFile());
                        /** @noinspection PhpUndefinedMethodInspection */
                        $image->interlace()->encode(str_replace('image/', '', $file->type), 90);
                    });
                }
            } catch (Throwable $e) {
                if ($e->getCode() === 0 && getenv('ENV') === 'dev') {
                    return $response->withStatus(302)->withRedirect('https://krafti.ru/image/' . implode('/', $args));
                }
                return $response->write($e->getMessage())->withStatus(500);
            }
            $response->write($image);

            return $response
                ->withHeader('Expires', $file->updated_at->addDay()->toRfc822String())
                ->withHeader('Content-Type', $file->type);
        }

        return $response->withStatus(404);
    }
}