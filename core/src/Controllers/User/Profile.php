<?php

namespace App\Controllers\User;


use App\Model\Traits\UserValidate;
use App\Model\User;
use Vesp\Controllers\Controller;

class Profile extends Controller
{
    use UserValidate;

    protected $scope = 'profile';
    /** @var User $user */
    protected $user;


    public function get()
    {
        $data = $this->user->getProfile();

        $this->user->logged_at = date('Y-m-d H:i:m');
        $this->user->save();

        return $this->success(['user' => $data]);
    }


    public function patch()
    {
        $user = $this->user;

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
                : ($user->children()->create($child))->getKey();
        }
        $user->children()->whereNotIn('id', $ids)->delete();

        return $this->get();
    }
}