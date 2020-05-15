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

    /**
     * @return ResponseInterface;
     */
    public function get()
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
            if ($id == -1) {
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
            } else {
                return $this->failure('Не могу загрузить бесплатный урок');
            }
        }

        return parent::get();
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->where(['active' => true, 'free' => true]);
        $c->select('id', 'title', 'video_id');
        $c->with('video:id,preview');

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->orderByRaw('RAND()');

        return $c;
    }
}
