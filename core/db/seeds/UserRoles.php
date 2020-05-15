<?php

use App\Model\UserRole;
use Phinx\Seed\AbstractSeed;

class UserRoles extends AbstractSeed
{
    public function run()
    {
        $roles = [
            'Администратор' => [
                'scope' => [
                    'profile',
                    'admin',
                    'courses',
                    'lessons',
                    'videos',
                    'comments',
                    'gallery',
                    'user-roles',
                    'users',
                    'homeworks',
                    'discounts',
                    'orders',
                ],
            ],
            'Автор' => [
                'scope' => ['profile', 'admin', 'courses', 'lessons', 'videos', 'comments', 'gallery', 'homeworks'],
            ],
            'Пользователь' => [
                'scope' => ['profile', 'lessons/get'],
            ],
        ];

        foreach ($roles as $group => $data) {
            if (!$record = UserRole::query()->where('title', '=', $group)->first()) {
                $record = new UserRole(['title' => $group]);
            }
            if (!empty($data)) {
                $record->fill($data);
            }
            $record->save();
        }
    }
}