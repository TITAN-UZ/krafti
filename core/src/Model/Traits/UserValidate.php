<?php

namespace App\Model\Traits;

use App\Model\User;

trait UserValidate
{

    public function validate(User $record)
    {
        if ($record->email) {
            if (!filter_var($record->email, FILTER_VALIDATE_EMAIL)) {
                return 'Неправильный адрес электронной почты';
            } elseif (User::query()->where('email', $record->email)->where('id', '!=', $record->id)->count()) {
                return 'Этот email уже есть у нас в базе. Укажите другой адрес, или сделайте сброс пароля.';
            }
        }

        if (!$record->fullname) {
            return 'Вы должны указать своё имя';
        }

        return true;
    }

}