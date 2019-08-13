<?php

namespace App\Processors\Admin;

use App\Model\User;
use Illuminate\Database\Eloquent\Builder;

class Users extends \App\ObjectProcessor
{

    protected $class = '\App\Model\User';
    protected $scope = 'users';


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        if ($query = $this->getProperty('query', '')) {
            $c->where(function (Builder $c) use ($query) {
                $c->where('email', 'LIKE', "%$query%");
                $c->orWhere('fullname', 'LIKE', "%$query%");
            });
        }

        if ($role_id = (int)$this->getProperty('role_id')) {
            $c->where('role_id', '=', $role_id);
        } elseif ($role = $this->getProperty('role')) {
            if ($role == 'author') {
                $c->where(function (Builder $c) use ($query) {
                    $c->where('role_id', '<=', 3);
                    $c->where('id', '>', 1);
                });
            }
        }

        $active = $this->getProperty('active');
        if ($active !== null) {
            $c->where('active', '=', (bool)$active);
        }

        $confirmed = $this->getProperty('confirmed');
        if ($confirmed !== null) {
            $c->where('confirmed', '=', (bool)$confirmed);
        }

        return $c;
    }


    /**
     * @param User $object
     *
     * @return array|void
     */
    public function prepareRow($object)
    {
        $array = $object->toArray();

        $array['photo'] = $object->photo
            ? $object->photo->getUrl()
            : null;

        /*$array['background'] = $object->background
            ? $object->background->getUrl()
            : null;*/

        return $array;
    }


    /**
     * @param User $record
     *
     * @return bool|string
     */
    protected function beforeSave($record)
    {
        if (!preg_match('#@#', $record->email)) {
            return 'Вы должны указать email';
        }
        if (!$this->getProperty('fullname')) {
            return 'Вы должны указать имя пользователя';
        }
        if (!$this->getProperty('role_id')) {
            $record->role_id = 3;
        }

        return true;
    }

}