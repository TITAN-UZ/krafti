<?php

namespace App\Processors\User;

use App\GetProcessor;
use App\Model\Course;
use App\Model\File;
use App\Model\Homework;
use Illuminate\Database\Eloquent\Builder;

class Homeworks extends GetProcessor
{
    protected $scope = 'profile';
    protected $class = 'App\Model\Homework';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeGet($c)
    {
        $c->where(['user_id' => $this->container->user->id]);

        return $c;
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeCount($c)
    {
        $c->where(['user_id' => $this->container->user->id]);
        $c->select(['id', 'course_id', 'lesson_id', 'file_id', 'section', 'comment']);

        if ($course_id = (int)$this->getProperty('course_id')) {
            $lesson_id = (int)$this->getProperty('lesson_id');
            $c->where([
                'course_id' => $course_id,
                'lesson_id' => $lesson_id ?: null,
            ]);
        } else {
            /*$c->with(['course' => function (Relation $query) {
                $query->select(['id', 'title']);
            }]);*/
            $c->with('course:id,title');
            $c->with('lesson:id,title,section,rank');
        }

        return $c;
    }


    /**
     * @param Homework $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = $object->toArray();
        $array['file'] = $object->file
            ? $object->file->getUrl()
            : null;
        /*$array['file_id'] = $object->file
            ? $object->file->getUrl()
            : null;*/

        return $array;
    }


    public function post()
    {
        /** @var Course $course */
        if (!$course_id = (int)$this->getProperty('course_id')) {
            return $this->failure('Вы должны указать id курса');
        } elseif (!$course = Course::query()->find($course_id)) {
            return $this->failure('Не могу загрузить указанный курс');
        } elseif (!$course->wasBought($this->container->user->id)) {
            return $this->failure('Вы должны купить этот курс перед отправкой своей работы');
        }

        if ($lesson_id = (int)$this->getProperty('lesson_id')) {
            $section = 0;
        } else {
            $section = (int)$this->getProperty('section');
        }

        $key = [
            'user_id' => $this->container->user->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson_id ?: null,
            'section' => $section,
        ];

        $coins = false;
        /** @var Homework $homework */
        if (!$homework = Homework::query()->where($key)->first()) {
            $coins = true;
            $homework = new Homework($key);
        }

        /** @var File $file */
        if (!$file = $homework->file) {
            $file = new File($key);
        }

        if ($id = $file->uploadFile($_FILES['file'], json_decode($_POST['metadata'], true))) {
            $homework->file_id = $id;
            $homework->save();
            $last_section = $homework->course->lessons()->max('section');

            if ($coins) {
                if (getenv('COINS_HOMEWORK')) {
                    $this->container->user->makeTransaction(getenv('COINS_HOMEWORK'), 'homework', [
                        'course_id' => $course->id,
                    ]);
                }
                if ($section == $last_section && getenv('COINS_PALETTE')) {
                    $this->container->user->makeTransaction(getenv('COINS_PALETTE'), 'palette', [
                        'course_id' => $course->id,
                    ]);
                }
            }

            $progress = $this->container->user->getProgress($course);
            if (!empty($section) && $progress->section == $section) {
                $new_section = $section >= $last_section
                    ? 0
                    : $section + 1;
                $this->container->user->makeProgress($course, $new_section, 0);
            }
        }

        $progress = $this->container->user->getProgress($course);
        $homeworks = $this->get();

        return $this->success([
            'progress' => [
                'section' => $progress->section,
                'rank' => $progress->rank,
            ],
            'homeworks' => @json_decode($homeworks->getBody()->__toString())->rows ?: [],
        ]);
    }

}