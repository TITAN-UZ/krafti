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
            } elseif ($order = $this->container->user->orders()->where('course_id', $object->id)->orderBy('paid_till', 'desc')->first()) {
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