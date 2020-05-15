<?php

namespace App\Controllers\Web;

use App\Model\Course;
use App\Model\Order;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelGetController;

class Courses extends ModelGetController
{
    protected $model = Course::class;
    /** @var User $user */
    protected $user;

    /**
     * @return ResponseInterface;
     */
    public function get()
    {
        if ($id = (int)$this->getPrimaryKey()) {
            /** @var Course $course */
            if (!$course = Course::query()->find($id)) {
                return $this->failure('Не могу загрузить курс');
            }
            if (!$course->active && !$course->wasBought($this->user)) {
                return $this->failure('Не могу загрузить курс');
            }
        }

        return parent::get();
    }

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
                    ->where('user_id', $this->user->id)
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

            if ($this->user) {
                $progress = $this->user->getProgress($object);
                $array['progress'] = [
                    'section' => $progress->section,
                    'rank' => $progress->rank,
                ];
            }
        }

        if ($this->user) {
            $array['bought'] = $object->wasBought($this->user);
            if (!$array['bought']) {
                $array['discount'] = $object->getDiscount($this->user);
            } else {
                $c = $this->user->orders()->where('course_id', $object->id)->orderBy('paid_till', 'desc');
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