<?php

use App\Model\User;
use Phinx\Seed\AbstractSeed;

class Users extends AbstractSeed
{
    public function run()
    {
        $users = [
            'admin' => [
                'password' => '2head',
                'fullname' => 'Администрация Krafti',
                'confirmed' => 1,
                'role_id' => 1,
            ],
            'bezumkin@ya.ru' => [
                'password' => '123456',
                'fullname' => 'Василий Наумкин',
                'confirmed' => 1,
                'role_id' => 1,
            ],
        ];

        foreach ($users as $key => $data) {
            if (!$record = User::query()->where('email', '=', $key)->first()) {
                $record = new User(['email' => $key]);
            }
            $record->fill($data)
                ->save();
        }
    }
}
