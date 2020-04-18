<?php

namespace App\Processors\Web;

use App\GetProcessor;
use App\Model\Course;
use App\Model\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courses extends GetProcessor
{
    protected $class = Course::class;

    /**
     * @param Builder $c
     * @return Builder|mixed
     */
    protected function beforeGet($c)
    {
        $c = $this->beforeCount($c);

        return $this->afterCount($c);
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->where('active', true);

        if ($exclude = $this->getProperty('exclude')) {
            $c->whereNotIn('id', explode(',', $exclude));
        }

        if ($category = trim($this->getProperty('category'))) {
            $c->where('category', $category);
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->select('id', 'title', 'tagline', 'description', 'category', 'price', 'age',
            'views_count', 'reviews_count', 'likes_sum', 'lessons_count', 'videos_count',
            'cover_id', 'video_id', 'template_id');
        $c->with('cover:id,updated_at');
        $c->with('video:id,remote_key');
        $c->with('template');
        $c->with([
            'lessons' => function (HasMany $c) {
                $c->where(['active' => true, 'free' => true])
                    ->orderByRaw('RAND()')
                    ->limit(1)
                    ->select('id', 'course_id');
            },
        ]);

        return $c;
    }


    /**
     * @param Course $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        /*$array = [
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
            'progress' => [
                'section' => 1,
                'rank' => 0,
            ],
            'free_lesson' => $object->lessons()
                ->where(['active' => true, 'free' => true])
                ->orderByRaw('RAND()')
                ->limit(1)
                ->first(['id']),
            'template' => $object->template->toArray(),
        ];*/
        $array = $object->toArray();
        $array['bought'] = false;
        $array['progress'] = [];

        if (isset($array['lessons'])) {
            $array['free_lesson'] = array_pop($array['lessons']);
            unset($array['lessons']);
        }
        unset($array['template_id'], $array['cover_id'], $array['video_id']);

        if ($this->container->user) {
            $array['bought'] = $object->wasBought($this->container->user);
            if (!$array['bought']) {
                $array['discount'] = $object->getDiscount($this->container->user);
            } elseif ($order = $this->container->user->orders()->where(['course_id' => $object->id])->first()) {
                /** @var Order $order */
                $array['paid_till'] = $order->paid_till;
            }
            $progress = $this->container->user->getProgress($object);
            $array['progress'] = [
                'section' => $progress->section,
                'rank' => $progress->rank,
            ];
        }

        return $array;
    }
}