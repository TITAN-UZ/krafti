<?php

namespace App\Processors\User;


use App\Model\Traits\UserValidate;
use App\Model\UserFavorite;
use App\Model\UserLike;
use App\Model\UserOauth;

class Profile extends \App\Processor
{
    use UserValidate;

    protected $scope = 'profile';


    public function get()
    {
        $user = $this->container->user;

        $favorites = $oauth2 = [];
        /** @var UserFavorite $obj */
        foreach ($user->favorites()->get() as $obj) {
            $favorites[] = $obj->course_id;
        }
        /** @var UserOauth $obj */
        foreach ($user->oauths()->get() as $obj) {
            $oauth2[$obj->provider] = $obj->displayName;
        }

        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'instagram' => $user->instagram,
            'company' => $user->company,
            'description' => $user->description,
            'fullname' => $user->fullname,
            'account' => $user->account,
            'dob' => $user->dob,
            'phone' => '+' . $user->phone,
            'scope' => $user->role->scope,
            'children' => $user->children,
            'photo' => $user->photo
                ? $user->photo->getUrl()
                : null,
            'background' => $user->background
                ? $user->background->getUrl()
                : null,
            'promo' => $user->promo,
            'favorites' => $favorites,
            'oauth2' => $oauth2,
        ];

        $data['unread'] = 0;

        $this->container->user->logged_at = date('Y-m-d H:i:m');
        $this->container->user->save();

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

        $children = $this->getProperty('children');
        if (is_array($children)) {
            $user->children = $children;
        }

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