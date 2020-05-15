<?php

namespace App\Controllers\User;

use App\Model\UserChild;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class Children extends ModelController
{
    protected $scope = 'profile';
    protected $model = UserChild::class;


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
        $c->where(['user_id' => $this->user->id]);

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
            $record->user_id = $this->user->id;
        } elseif ($record->user_id != $this->user->id) {
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
        if ($record->user_id != $this->user->id) {
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
        return [
            'id' => $object->id,
            'name' => $object->name,
            'dob' => $object->dob,
            'gender' => $object->gender,
        ];
    }

}