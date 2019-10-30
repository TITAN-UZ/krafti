<?php

namespace App\Processors\Web;

use App\Model\Course;
use App\Model\Lesson;
use App\Model\Order;
use App\Model\UserProgress;
use Illuminate\Database\Eloquent\Builder;

class Courses extends \App\GetProcessor
{

    protected $class = '\App\Model\Course';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->where(['active' => true]);

        if ($exclude = $this->getProperty('exclude')) {
            $c->whereNotIn('id', explode(',', $exclude));
        }

        if ($category = trim($this->getProperty('category'))) {
            $c->where(['category' => $category]);
        }

        return $c;
    }


    /**
     * @param Course $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'title' => $object->title,
            'tagline' => $object->tagline,
            'description' => $object->description,
            'category' => $object->category,
            'price' => $object->price,
            'age' => $object->age,
            'views_count' => $object->views_count,
            'reviews_count' => $object->reviews_count,
            'likes_sum' => $object->likes_sum,
            'lessons_count' => $object->lessons_count,
            'videos_count' => $object->videos_count,
            'cover' => $object->cover
                ? $object->cover->getUrl()
                : null,
            'video' => $object->video
                ? [
                    'id' => $object->video->id,
                    'remote_key' => $object->video->remote_key,
                ]
                : null,
            'bought' => false,
            'discount' => 0,
            'progress' => [
                'section' => 1,
                'rank' => 0,
            ],
        ];

        if ($this->container->user) {
            $array['bought'] = $object->wasBought($this->container->user->id);
            if (!$array['bought']) {
                $array['discount'] = $object->getDiscount($this->container->user->id);
            } elseif ($order = $this->container->user->orders()->where(['course_id' => $object->id])->first()) {
                /** @var Order $order */
                $array['paid_till'] = $order->paid_till->toIso8601String();
            }
            /** @var UserProgress $progress */
            if ($progress = $object->progresses()->where(['user_id' => $this->container->user->id])->first()) {
                $array['progress'] = [
                    'section' => $progress->section,
                    'rank' => $progress->rank,
                ];
            }
        } elseif ($free = $object->lessons()->where(['free' => true])->get()) {
            /** @var Lesson $item */
            foreach ($free as $item) {
                $array['free'][] = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'video' => $item->video
                        ? ['vimeo' => $item->video->remote_key]
                        : null,
                ];
            }
        }

        return $array;
    }
}