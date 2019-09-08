<?php

namespace App\Processors\User;

use App\ObjectProcessor;
use App\Model\UserChild;
use Illuminate\Database\Eloquent\Builder;

class Children extends ObjectProcessor
{
    protected $scope = 'profile';
    protected $class = 'App\Model\UserChild';


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
        $c->where(['user_id' => $this->container->user->id]);

        return $c;
    }


    /**
     * @param UserChild $record
     *
     * @return bool|string
     */
    protected function beforeSave($record)
    {
        if (!$record->exists) {
            $record->user_id = $this->container->user->id;
        } elseif ($record->user_id != $this->container->user->id) {
            return 'Это не ваш ребёнок';
        }

        if (empty($record->name)) {
            return 'Вы должны указать имя ребёнка';
        }
        if (empty($record->dob)) {
            return 'Вы должны указать дату рождения ребёнка';
        }

        return true;
    }


    /**
     * @param UserChild $record
     *
     * @return bool|string
     */
    protected function beforeDelete($record)
    {
        if ($record->user_id != $this->container->user->id) {
            return 'Это не ваш ребёнок';
        }

        return true;
    }


    /**
     * @param UserChild $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'name' => $object->name,
            'dob' => $object->dob,
            'gender' => $object->gender,
        ];

        return $array;
    }

}