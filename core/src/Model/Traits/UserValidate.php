<?php

namespace App\Model\Traits;

use App\Model\User;

trait UserValidate {

    public function validate(User $record) {
        if (!$record->email || !preg_match('#.+@.+\..+#', $record->email)) {
            return 'Вы должны указать адрес электронной почты';
        } elseif (!$record->id && User::query()->where(['email' => $record->email])->count()) {
            return 'Этот email уже есть у нас в базе. Укажите другой адрес, или сделайте сброс пароля.';
        }

        if (!$record->fullname) {
            return 'Вы должны указать своё имя';
        }

        return true;
    }

}