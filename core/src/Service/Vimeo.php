<?php

namespace App\Service;

use App\Container;
use App\Model\Video;
use Exception;

class Vimeo
{

    public $container;
    const owner = '100836496';


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    /**
     *
     */
    public function import()
    {
        $new = $updated = 0;
        $ids = [];
        if ($videos = $this->loadVideos()) {
            foreach ($videos as $video) {
                if (!$object = Video::query()->where(['remote_key' => $video['remote_key']])->first()) {
                    $object = new Video();
                    $new++;
                } else {
                    $updated++;
                }
                $object->fill($video);
                $object->save();

                $ids[] = $object->remote_key;
            }

            $this->container->logger->info('Processed all Vimeo videos', [
                'new' => $new,
                'updated' => $updated,
            ]);

            Video::query()->whereNotIn('remote_key', $ids)->delete();
        }
    }


    /**
     * @param int $page
     * @param int $per_page
     *
     * @return array
     */
    public function loadVideos($page = 1, $per_page = 100)
    {
        $data = [];
        try {
            $res = $this->container->vimeo->request('/users/' . $this::owner . '/videos', [
                'per_page' => $per_page,
                'page' => $page,
            ]);

            $total = $res['body']['total'];

            foreach ($res['body']['data'] as $video) {

                if (!preg_match('#http.*?(\d+)($|/)#', $video['link'], $matches)) {
                    $this->container->logger->error('Could not get Vimeo id: ' . $video['link']);
                    continue;
                }

                $preview = [];
                foreach ($video['pictures']['sizes'] as $picture) {
                    $size = implode('x', [$picture['width'], $picture['height']]);
                    $preview[$size] = $picture['link'];
                }
                //$video['privacy']

                $data[] = [
                    'title' => $video['name'],
                    'description' => $video['description'],
                    'remote_key' => $matches[1],
                    'duration' => $video['duration'],
                    'preview' => $preview,
                    'width' => $video['width'],
                    'height' => $video['height'],
                    'views_count' => @$video['stats']['plays'],
                ];
            }

            if (($page * $per_page) < $total) {
                $data = array_merge($data, $this->loadVideos($page + 1, $per_page));
            }
        } catch (Exception $e) {
            $this->container->logger->error($e->getMessage());
        }

        return $data;
    }

}