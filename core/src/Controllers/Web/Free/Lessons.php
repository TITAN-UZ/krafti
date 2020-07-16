<?php

namespace App\Controllers\Web\Free;

use App\Model\Lesson;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelGetController;

class Lessons extends ModelGetController
{
    protected $model = Lesson::class;

    public function get(): ResponseInterface
    {
        if ($id = $this->getPrimaryKey()) {
            /** @var Model $class */
            $class = new $this->model();
            $c = $class->newQuery();

            $c->where(['active' => true, 'free' => true]);
            $c->with('course:id,title,price');
            $c->with('video:id,title,remote_key');
            $c->with('bonus:id,title,remote_key');
            $c->with('file:id,updated_at');
            $c->with('author:id,fullname,photo_id', 'author.photo:id,updated_at');
            if ($id === -1) {
                if ($course_id = $this->getProperty('course_id')) {
                    $c->where('course_id', $course_id);
                }
                $c->orderByRaw('RAND()');
                $c->limit(1);
            } else {
                $c->where('id', $id);
            }

            if ($obj = $c->first()) {
                return $this->success($obj->toArray());
            }

            return $this->failure('Не могу загрузить бесплатный урок', 404);
        }

        return parent::get();
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->where(['active' => true, 'free' => true]);
        $c->select('id', 'title', 'video_id');
        $c->with('video:id,preview');

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->orderByRaw('RAND()');

        return $c;
    }
}
