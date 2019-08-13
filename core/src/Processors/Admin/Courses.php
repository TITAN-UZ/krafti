<?php

namespace App\Processors\Admin;

use App\Model\Course;
use App\Model\File;
use Illuminate\Database\Eloquent\Builder;

class Courses extends \App\ObjectProcessor
{

    protected $class = '\App\Model\Course';
    protected $scope = 'courses';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where(function (Builder $c) use ($query) {
                $c->where('title', 'LIKE', "%$query%");
                $c->orWhere('description', 'LIKE', "%$query%");
            });
        }

        return $c;
    }


    /**
     * @param Course $record
     *
     * @return bool|string
     */
    protected function beforeSave($record)
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

        if ($cover = $this->getProperty('cover')) {
            if (is_array($cover) && !empty($cover['file'])) {
                if (!$file = $record->cover) {
                    $file = new File();
                }

                if ($id = $file->uploadBase64($cover['file'], $cover['metadata'])) {
                    $record->cover_id = $id;
                }
            }
        }

        return true;
    }


    /**
     * @param Course $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'title' => $object->title,
            'tagline' => $object->tagline,
            'description' => $object->description,
            //'cover_id' => $object->cover_id,
            'video_id' => $object->video_id,
            'bonus_id' => $object->bonus_id,
            'category' => $object->category,
            'price' => $object->price,
            'age' => $object->age,
            'active' => $object->active,
            'cover' => $object->cover
                ? $object->cover->getUrl()
                : '',
            'video' => $object->video
                ? $object->video->preview
                : '',
            'bonus' => $object->bonus
                ? $object->bonus->preview
                : '',
        ];

        return $array;
    }
}