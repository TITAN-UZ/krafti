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
        $c->select(
            'id', 'title', 'tagline', 'description', 'category', 'price', 'age',
            'views_count', 'reviews_count', 'likes_sum', 'lessons_count', 'videos_count',
            'cover_id', 'video_id', 'template_id'
        );

        $c->where('active', true);
        $c->with('video:id,remote_key');
        $c->with('cover:id,updated_at');
        $c->with('template');
        $c->with([
            'lessons' => function (HasMany $c) {
                $c->where(['active' => true, 'free' => true])
                    ->orderByRaw('RAND()')
                    ->limit(1)
                    ->select('id', 'course_id');
            },
            'homeworks' => function (HasMany $c) {
                $c->whereNull('lesson_id')
                    ->where('user_id', $this->container->user->id)
                    ->select('id', 'course_id', 'file_id', 'section')
                    ->with('file:id,updated_at');
            },
        ]);

        return $c;
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
        $c->select('id', 'title', 'tagline', 'description', 'category', 'price', 'lessons_count', 'cover_id');
        $c->with('cover:id,updated_at');

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

        if ($this->getProperty('id')) {
            $array['progress'] = [];
            if (isset($array['lessons'])) {
                $array['free_lesson'] = array_pop($array['lessons']);
                unset($array['lessons']);
            }

            if ($this->container->user) {
                $progress = $this->container->user->getProgress($object);
                $array['progress'] = [
                    'section' => $progress->section,
                    'rank' => $progress->rank,
                ];
            }
        }

        if ($this->container->user) {
            $array['bought'] = $object->wasBought($this->container->user);
            if (!$array['bought']) {
                $array['discount'] = $object->getDiscount($this->container->user);
            } else {
                $c = $this->container->user->orders()->where('course_id', $object->id)->orderBy('paid_till', 'desc');
                if ($order = $c->first()) {
                    /** @var Order $order */
                    $array['paid_till'] = $order->paid_till;
                }
            }
        }
        unset($array['template_id'], $array['cover_id'], $array['video_id']);

        return $array;
    }
}