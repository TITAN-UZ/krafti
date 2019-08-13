<?php

namespace App\Processors\Admin;

use App\Model\Lesson;
use Illuminate\Database\Eloquent\Builder;

class Lessons extends \App\ObjectProcessor
{

    protected $class = '\App\Model\Lesson';
    protected $scope = 'lessons';


    /*protected function beforeSave($record)
    {
        if (!$title = trim($this->getProperty('title'))) {
            return 'Вы должны указать название курса';
        } elseif (Course::query()->where(['title' => $title])->where('id', '!=', $record->id)->count()) {
            return 'Название курса должно быть уникальным';
        }

        if (!trim($this->getProperty('category'))) {
            return 'Вы должны указать категорию курса';
        }

        if (!$age = trim($this->getProperty('age'))) {
            return 'Вы должны указать возраст аудитории курса';
        } elseif (!preg_match('#(\d+)\-(\d+)#', $age)) {
            return 'Неверный формат возраста. Укажите 2 числа: от и до, через дефис.';
        }

        return true;
    }*/
    /**
     * @param Builder $c
     *
     * @return mixed
     */
    protected function beforeCount($c)
    {
        if ($course_id = (int)$this->getProperty('course_id')) {
            $c->where(['course_id' => $course_id]);
        }
        if ($query = trim($this->getProperty('query'))) {
            $c->where(function (Builder $c) use ($query) {
                $c->where('title', 'LIKE', "%$query%");
                $c->orWhere('description', 'LIKE', "%$query%");
            });
        }

        return $c;
    }


    /**
     * @param Lesson $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'title' => $object->title,
            'description' => $object->description,
            'rank' => $object->rank,
            'products' => $object->products,
            'course_id' => $object->course_id,
            'course' => $object->course
                ? $object->course->title
                : '',
            'video_id' => $object->video_id,
            'video' => $object->video
                ? $object->video->preview
                : '',
            'bonus_id' => $object->bonus_id,
            'bonus' => $object->bonus
                ? $object->bonus->preview
                : '',
            'file_id' => $object->file_id,
            'file' => $object->file
                ? $object->file->getUrl()
                : '',
            'author_id' => $object->author_id,
            'author' => $object->author
                ? $object->author->fullname
                : '',
            'active' => $object->active,
        ];

        return $array;
    }
}