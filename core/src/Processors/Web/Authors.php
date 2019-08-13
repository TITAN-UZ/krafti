<?php

namespace App\Processors\Web;

use App\Model\User;

class Authors extends \App\GetProcessor
{

    protected $class = '\App\Model\User';


    protected function beforeCount($c)
    {
        $c->where(['active' => true]);

        return $c;
    }


    /**
     * @param User $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = $object->toArray();

        $array['photo'] = $object->photo
            ? $object->photo->getUrl()
            : null;

        return $array;
    }
}