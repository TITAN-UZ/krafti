<?php

namespace App\Processors\Web;

use App\Model\Comment;
use Illuminate\Database\Eloquent\Builder;

class Reviews extends \App\GetProcessor
{

    protected $class = '\App\Model\Comment';


    public function beforeGet($c)
    {
        $c->where(['review' => true, 'deleted' => false]);
        $c->with('user');

        return $c;
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeCount($c)
    {
        $c->groupBy('user_id');
        $c->where(['review' => true, 'deleted' => false]);
        $c->with('user');

        return $c;
    }


    /**
     * @param Comment $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'text' => $object->text,
            'user' => [
                'id' => $object->user->id,
                'fullname' => $object->user->fullname,
                'company' => $object->user->company,
                'photo' => $object->user->photo
                    ? $object->user->photo->getUrl()
                    : null,
            ],
        ];

        return $array;
    }

}