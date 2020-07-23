<?php

namespace App\Controllers\Web;

use App\Model\Course;
use App\Model\Order;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelGetController;

class Courses extends ModelGetController
{
    protected $model = Course::class;

    /** @var User $user */
    protected $user;

    public function get(): ResponseInterface
    {
        if ($id = (int)$this->getPrimaryKey()) {
            /** @var Course $course */
            if (!$course = Course::query()->find($id)) {
                return $this->failure('Не могу загрузить курс', 404);
            }
            if (!$course->active && !$course->wasBought($this->user)) {
                return $this->failure('Не могу загрузить курс', 404);
            }
        }

        return parent::get();
    }

    protected function beforeGet(Builder $c): Builder
    {
        $c->select(
            'id',
            'title',
            'tagline',
            'description',
            'instruments',
            'category',
            'price',
            'age',
            'views_count',
            'reviews_count',
            'likes_sum',
            'lessons_count',
            'videos_count',
            'cover_id',
            'video_id',
            'template_id'
        );

        $c->with('video:id,remote_key');
        $c->with('cover:id,updated_at');
        $c->with('template');
        $c->with([
            'lessons' => static function (HasMany $c) {
                $c->where(['active' => true, 'free' => true]);
                $c->orderByRaw('RAND()');
                $c->limit(1);
                $c->select('id', 'course_id');
            },
        ]);
        if ($this->user) {
            $c->with([
                'homeworks' => function (HasMany $c) {
                    $c->whereNull('lesson_id');
                    $c->where('user_id', $this->user->id);
                    $c->select('id', 'course_id', 'file_id', 'section');
                    $c->with('file:id,updated_at');
                },
            ]);
        }

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
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

    protected function afterCount(Builder $c): Builder
    {
        $c->select('id', 'title', 'tagline', 'description', 'category', 'price', 'lessons_count', 'cover_id');
        $c->with('cover:id,updated_at');

        return $c;
    }

    /**
     * @param Course|Model $object
     *
     * @return array
     */
    public function prepareRow(Model $object): array
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
