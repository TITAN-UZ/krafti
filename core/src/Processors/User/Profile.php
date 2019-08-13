<?php

namespace App\Processors\User;


use App\Model\UserFavorite;

class Profile extends \App\Processor
{
    protected $scope = 'profile';


    public function get()
    {
        $user = $this->container->user;
        $favorites = [];
        /** @var UserFavorite $obj */
        foreach ($user->favorites()->get() as $obj) {
            $favorites[] = $obj->course_id;
        }

        $data = [
            'email' => $user->email,
            'instagram' => $user->instagram,
            'fullname' => $user->fullname,
            'coins' => $user->coins,
            'phone' => '+' . $user->phone,
            'scope' => $user->role->scope,
            'photo' => $user->photo
                ? $user->photo->getUrl()
                : null,
            'background' => $user->background
                ? $user->background->getUrl()
                : null,
            'favorites' => $favorites ?: [],
        ];

        $data['unread'] = 999;

        return $this->success([
            'user' => $data,
        ]);
    }


    public function patch()
    {
        $this->container->user->fill([
            'email' => trim($this->getProperty('email')),
            'dob' => $this->getProperty('dob'),
            'fullname' => trim($this->getProperty('fullname')),
            'instagram' => trim($this->getProperty('instagram'), ' @'),
            'phone' => preg_replace('#[^0-9]#', '', $this->getProperty('phone')),
        ]);
        if ($password = trim($this->getProperty('password'))) {
            $this->container->user->password = $password;
        }
        $this->container->user->save();

        return $this->get();
    }
}