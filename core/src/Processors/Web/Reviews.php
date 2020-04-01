<?php

namespace App\Processors\Web;

use App\GetProcessor;
use App\Model\Comment;
use Illuminate\Database\Eloquent\Builder;

class Reviews extends GetProcessor
{

    protected $class = '\App\Model\Comment';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
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
        $c->with('user:id,fullname,company,photo_id');
        if ($this->getProperty('limit') < 20) {
            $c->orderByRaw('RAND()');
        }

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