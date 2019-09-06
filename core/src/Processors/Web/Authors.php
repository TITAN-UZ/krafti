<?php

namespace App\Processors\Web;

use App\Model\User;
use Illuminate\Database\Eloquent\Builder;

class Authors extends \App\GetProcessor
{

    protected $class = '\App\Model\User';


    /**
     * Add joins and search filter
     *
     * @param Builder $c
     *
     * @return mixed
     */
    protected function beforeGet($c)
    {
        return $this->beforeCount($c);
    }


    /**
     * Add joins and search filter
     *
     * @param Builder $c
     *
     * @return mixed
     */
    protected function beforeCount($c)
    {
        $c->where(['users.active' => true, 'favorite' => true]);
        //->groupBy('users.id')
        //->where('users.role_id', '<', 3)
        //->select(['users.id', 'users.fullname', 'users.company', 'users.description', 'users.photo_id'])
        //->join('lessons', 'lessons.author_id', '=', 'users.id');

        return $c;
    }


    /**
     * @param User $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'fullname' => $object->fullname,
            'company' => $object->company,
            'description' => $object->description,
            'long_description' => $object->long_description,
            'photo' => $object->photo
                ? $object->photo->getUrl()
                : null,
        ];

        return $array;
    }
}