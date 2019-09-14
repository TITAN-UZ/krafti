<?php

namespace App\Processors\Admin;

use App\Model\File;
use App\Model\Traits\UserValidate;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;

class Users extends \App\ObjectProcessor
{
    use UserValidate;
    protected $class = '\App\Model\User';
    protected $scope = 'users';


    /**
     * @return \Slim\Http\Response
     */
    public function post()
    {
        if (!$user_id = (int)$this->getProperty('user_id')) {
            return $this->failure('Вы должны указать id пользователя');
        }

        /** @var User $user */
        if (!$user = User::query()->find($user_id)) {
            return $this->failure('Не могу загрузить указанного пользователя');
        }

        if (!$file = $user->photo) {
            $file = new File();
        }
        if ($id = $file->uploadFile($_FILES['file'], json_decode($_POST['metadata'], true))) {
            $user->photo_id = $id;
            $user->save();
        } else {
            return $this->failure('Не могу загрузить файл');
        }
        $user = User::query()->find($user->id);

        return $this->success([
            'photo' => $user->photo->getUrl(),
        ]);
    }


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
                $c->orWhere('instagram', 'LIKE', "%$query%");
            });
        }

        $c->where('id', '>', 1);
        if ($role_id = $this->getProperty('role_id')) {
            if (is_array($role_id)) {
                $c->whereIn('role_id', $role_id);
            } else {
                $c->where(['role_id' => $role_id]);
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


    /*
     * @param Builder $c
     *
     * @return Builder
     */
    public function afterCount($c)
    {
        $c->withCount('referrals');

        if ($this->getProperty('sort') == 'referrals_count') {
            $c->orderByRaw($this->container->db->raw('referrals_count ' . $this->getProperty('dir')));
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

        return $array;
    }


    /**
     * @return \Slim\Http\Response
     */
    public function patch()
    {
        if (!$this->getProperty('password')) {
            $this->unsetProperty('password');
        }

        return parent::patch();
    }


    /**
     * @param User $record
     *
     * @return bool|string
     */
    protected function beforeSave($record)
    {
        if (!$this->getProperty('role_id')) {
            $record->role_id = 3;
        }

        return $this->validate($record);
    }


    /**
     * @return bool|\Slim\Http\Response
     */
    public function delete()
    {
        if (!$id = $this->getProperty($this->primaryKey)) {
            return $this->failure('Не указан id записи');
        }

        /** @var User $record */
        if (!$record = User::query()->find($id)) {
            return $this->failure('Не могу найти запись');
        }
        $record->active = false;
        $record->save();

        return true;
    }

}