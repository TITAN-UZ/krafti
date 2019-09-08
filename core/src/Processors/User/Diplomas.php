<?php

namespace App\Processors\User;

use App\GetProcessor;
use App\Model\Diploma;
use Illuminate\Database\Eloquent\Builder;

class Diplomas extends GetProcessor
{
    protected $scope = 'profile';
    protected $class = 'App\Model\Diploma';


    /**
     * @param Builder $c
     *
     * @return Builder|mixed
     */
    protected function beforeGet($c)
    {
        return $this->beforeCount($c);
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->where(['user_id' => $this->container->user->id])
            ->whereNotNull('file_id');
        $c->with('course:id,title');
        $c->with('child:id,name,dob');

        return $c;
    }


    /**
     * @param Diploma $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'file' => $object->file->getUrl(),
            'child' => $object->child,
            'course' => $object->course,
        ];

        return $array;
    }

}