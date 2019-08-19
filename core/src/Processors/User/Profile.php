<?php

namespace App\Processors\User;


use App\Model\Traits\UserValidate;
use App\Model\UserFavorite;
use App\Model\UserLike;

class Profile extends \App\Processor
{
    use UserValidate;

    protected $scope = 'profile';


    public function get()
    {
        $user = $this->container->user;

        $favorites = $likes = [];
        /** @var UserFavorite $obj */
        foreach ($user->favorites()->get() as $obj) {
            $favorites[] = $obj->course_id;
        }
        /** @var UserLike $obj */
        foreach ($user->likes()->get() as $obj) {
            $likes[] = $obj->course_id;
        }

        $data = [
            'email' => $user->email,
            'instagram' => $user->instagram,
            'company' => $user->company,
            'description' => $user->description,
            'fullname' => $user->fullname,
            'coins' => $user->coins,
            'dob' => $user->dob,
            'phone' => '+' . $user->phone,
            'scope' => $user->role->scope,
            'photo' => $user->photo
                ? $user->photo->getUrl()
                : null,
            'background' => $user->background
                ? $user->background->getUrl()
                : null,
            'favorites' => $favorites ?: [],
            'likes' => $likes ?: [],
        ];

        $data['unread'] = 0;

        return $this->success([
            'user' => $data,
        ]);
    }


    public function patch()
    {
        $user = $this->container->user;
        $user->fill([
            'email' => trim($this->getProperty('email')),
            'dob' => $this->getProperty('dob'),
            'fullname' => trim($this->getProperty('fullname')),
            'instagram' => trim($this->getProperty('instagram'), ' @'),
            'phone' => preg_replace('#[^0-9]#', '', $this->getProperty('phone')),
            'company' => trim($this->getProperty('company')),
            'description' => trim($this->getProperty('description')),
        ]);
        $validate = $this->validate($user);
        if ($validate !== true) {
            return $this->failure($validate);
        }

        if ($password = trim($this->getProperty('password'))) {
            $user->password = $password;
        }
        $user->save();

        return $this->get();
    }
}