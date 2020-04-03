<?php

namespace App\Processors\User;


use App\Model\Traits\UserValidate;
use App\Model\UserFavorite;
use App\Model\UserOauth;
use App\Processor;

class Profile extends Processor
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
            'photo' => $user->photo
                ? [
                    'id' => $user->photo->id,
                    'updated_at' => $user->photo->updated_at,
                ]
                : null,
            'background' => $user->background
                ? [
                    'id' => $user->background->id,
                    'updated_at' => $user->background->updated_at,
                ]
                : null,
            'promo' => $user->promo,
            'favorites' => $favorites,
            'oauth2' => $oauth2,
            'children' => $user->children()->get(['id', 'name', 'dob', 'gender'])->toArray(),
            'unread' => $user->messages()->where('read', false)->count(),
        ];
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

        $validate = $this->validate($user);
        if ($validate !== true) {
            return $this->failure($validate);
        }

        if ($password = trim($this->getProperty('password'))) {
            $user->password = $password;
        }
        $user->save();

        $children = $this->getProperty('children');
        $ids = [];
        foreach ($children as $child) {
            $ids[] = !empty($child['id'])
                ? $child['id']
                : ($user->children()->create($child))->id;
        }
        $user->children()->whereNotIn('id', $ids)->delete();

        return $this->get();
    }
}