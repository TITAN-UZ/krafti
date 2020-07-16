<?php

namespace App\Controllers\Admin;

use App\Model\File;
use App\Model\Traits\UserValidate;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Users extends ModelController
{
    use UserValidate;

    protected $model = User::class;
    protected $scope = 'users';

    /**
     * @return ResponseInterface;
     */
    public function patch(): ResponseInterface
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
        $validate = $this->validate($record);
        if ($validate !== true) {
            return $validate;
        }

        if ($record->id == $this->user->id && !$record->active) {
            return 'Вы не можете отключить свой аккаунт';
        }

        if ($photo = $this->getProperty('new_photo', $this->getProperty('photo'))) {
            if (is_array($photo) && !empty($photo['file'])) {
                if (!$file = $record->photo) {
                    $file = new File();
                }

                if ($file->uploadFile($photo['file'], $photo['metadata'])) {
                    $record->photo_id = $file->id;
                }
            }
        }

        return true;
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where(static function (Builder $c) use ($query) {
                $c->where('email', 'LIKE', "%$query%");
                $c->orWhere('fullname', 'LIKE', "%$query%");
                $c->orWhere('instagram', 'LIKE', "%$query%");
            });
        }

        if ($role_id = $this->getProperty('role_id')) {
            if (is_array($role_id)) {
                $c->whereIn('role_id', $role_id);
            } else {
                $c->where('role_id', $role_id);
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

        if ($this->getProperty('combo')) {
            $c->select('id', 'fullname', 'photo_id');
        }

        return $c;
    }

    /*
     * @param Builder $c
     *
     * @return Builder
     */
    public function afterCount(Builder $c): Builder
    {
        $c->with('photo:id,updated_at');
        if (!$this->getProperty('combo')) {
            $c->withCount('referrals');
            $c->withCount([
                'orders as orders_count' => static function (Builder $c) {
                    $c->where('status', 2);
                    $c->where('service', '!=', 'internal');
                },
            ]);
            $c->with('role:id,title');
        }

        return $c;
    }

    /**
     * @param User $record
     * @return bool|string
     */
    protected function beforeDelete($record)
    {
        if ($record->orders()->where('status', 2)->where('service', '!=', 'internal')->count()) {
            return 'Нельзя удалять пользователя с оплаченными заказами';
        }
        if ($record->id == $this->user->id) {
            return 'Вы не можете удалить себя';
        }

        return true;
    }
}
