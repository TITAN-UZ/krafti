<?php

namespace App\Processors\Admin;

use App\Model\Comment;
use Illuminate\Database\Eloquent\Builder;

class Comments extends \App\ObjectProcessor
{
    protected $class = '\App\Model\Comment';
    protected $scope = 'users';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        if ($query = $this->getProperty('query', '')) {
            $c->where('text', 'LIKE', "%{$query}%");
        }

        $active = $this->getProperty('active');
        if ($active !== null) {
            $c->where('active', '=', (bool)$active);
        }

        $c->with('lesson:id,title,course_id');
        $c->with('user:id,fullname,photo_id');

        return $c;
    }


    /**
     * @param Comment $object
     *
     * @return array|void
     */
    public function prepareRow($object)
    {
        $array = $object->toArray();
        if ($object->user->photo) {
            $array['user']['photo'] = $object->user->photo->getUrl();
        }
        if ($object->lesson->course) {
            $array['course']['id'] = $object->lesson->course->id;
            $array['course']['title'] = $object->lesson->course->title;
        }
        $array['created_at'] = $object->created_at->toIso8601String();

        return $array;
    }

}