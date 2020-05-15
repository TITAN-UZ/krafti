<?php

namespace App\Controllers\Web\Course;

use App\Model\Course;
use App\Model\Lesson;
use App\Model\User;
use App\Model\UserProgress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vesp\Controllers\ModelGetController;

class Lessons extends ModelGetController
{
    protected $model = Lesson::class;
    protected $scope = 'profile';
    /** @var Course $course */
    protected $course;
    /** @var User $user */
    protected $user;
    /** @var UserProgress $progress */
    protected $progress;

    public function get()
    {
        /** @var Course $course */
        if (!$course = Course::query()->find((int)$this->getProperty('course_id'))) {
            return $this->failure('Не могу загрузить курс');
        }
        if (!$course->wasBought($this->user)) {
            return $this->failure(!$course->active ? 'Не могу загрузить курс' : 'Вы забыли оплатить этот курс');
        }

        $this->setProperty('limit', 0);
        $this->course = $course;
        $this->progress = $this->user->getProgress($course);

        if ($id = (int)$this->getPrimaryKey()) {
            /** @var Lesson $lesson */
            if ($lesson = Lesson::query()->find($id)) {
                if (!$lesson->checkAccess($this->progress)) {
                    return $this->failure('Вы еще не открыли этот урок');
                }
            }
        }

        return parent::get();
    }

    public function beforeGet($c)
    {
        $c->where(['active' => true, 'course_id' => $this->course->id]);
        $c->with([
            'course:id,title,template_id',
            'course.template',
            'course.progresses' => function (HasMany $c) {
                $c->where('user_id', $this->user->id);
                $c->select('course_id', 'section', 'rank');
            },
        ]);
        $c->with('video:id,remote_key,title,description,preview');
        $c->with('bonus:id,remote_key,title,description,preview');
        $c->with('author:id,fullname,company,description,photo_id', 'author.photo:id,updated_at');
        $c->with('file:id,updated_at');
        $c->with([
            'likes' => function (HasMany $c) {
                $c->where('user_id', $this->user->id);
                $c->select('lesson_id', 'value');
            },
            'homeworks' => function (HasMany $c) {
                $c->where('user_id', $this->user->id);
                $c->select('id', 'lesson_id', 'file_id');
                $c->with('file:id,updated_at');
            },
        ]);

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    public function beforeCount($c)
    {
        $c->where(['course_id' => $this->course->id, 'active' => true]);
        if ($section = (int)$this->getProperty('section')) {
            $c->where('section', $section);
        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    public function afterCount($c)
    {
        $c->orderBy('section', 'asc')->orderBy('rank', 'asc');
        $c->select('id', 'title', 'description', 'section', 'rank', 'extra', 'free', 'views_count', 'likes_count',
            'video_id');
        $c->with('video:id,preview');

        return $c;
    }

    /**
     * @param Lesson $object
     * @return array
     */
    public function prepareRow($object)
    {
        $array = $object->toArray();
        $array['locked'] = !$object->checkAccess($this->progress);

        if (isset($array['homeworks'])) {
            $array['homework'] = array_pop($array['homeworks']);
            unset($array['homeworks']);
        }
        if (isset($array['course']['progresses'])) {
            $array['course']['progress'] = array_pop($array['course']['progresses']);
            unset($array['course']['progresses']);
        }
        if (isset($array['likes'])) {
            $array['like'] = array_pop($array['likes']);
            unset($array['likes']);
        }

        return $array;
    }
}