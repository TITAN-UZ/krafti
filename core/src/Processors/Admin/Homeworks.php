<?php

namespace App\Processors\Admin;

use App\Model\Homework;
use Illuminate\Database\Eloquent\Builder;

class Homeworks extends \App\ObjectProcessor
{

    protected $class = '\App\Model\Homework';
    protected $scope = 'homeworks';


    public function put()
    {
        return $this->failure('Домашние работы создают пользователи');
    }


    public function delete()
    {
        return $this->failure('Домашние работы нельзя удалять');
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        if ($query = trim($this->getProperty('query', ''))) {
            $c->where(function (Builder $c) use ($query) {
                $c->whereHas('lesson', function (Builder $c) use ($query) {
                    $c->where('title', 'LIKE', "%$query%");
                });
                $c->orWhereHas('user', function (Builder $c) use ($query) {
                    $c->where('fullname', 'LIKE', "%$query%");
                    $c->orWhere('email', 'LIKE', "%$query%");
                });
            });
        }
        if ($course_id = $this->getProperty('course_id')) {
            $c->where(['course_id' => $course_id]);
        }
        if ($work_type = $this->getProperty('work_type')) {
            switch ($work_type) {
                case'work':
                    $c->whereNotNull('lesson_id');
                    break;
                case'home':
                    $c->whereNull('lesson_id');
                    break;
            }
        }
        if ($date = $this->getProperty('date')) {
            $c->whereBetween('created_at', $date);
        }

        $c->with('user:id,fullname,photo_id');
        $c->with('course:id,title');
        $c->with('lesson:id,title');

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
        $array['created_at'] = $object->created_at->toIso8601String();
        $array['file'] = $object->file->getUrl();
        unset($array['updated_at']);

        return $array;
    }
}