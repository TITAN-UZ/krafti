<?php

namespace App\Controllers\User;

use App\Model\File;
use App\Model\User;
use Vesp\Controllers\Controller;

class Picture extends Controller
{
    protected $scope = 'profile';

    /** @var User $user */
    protected $user;

    public function post()
    {
        if (!$file = $this->user->photo) {
            $file = new File();
        }

        $uploaded = $this->request->getUploadedFiles();
        if ($file->uploadFile(array_pop($uploaded), json_decode($_POST['metadata'], true))) {
            $this->user->photo_id = $file->id;
            $this->user->save();

            $this->user = User::query()->find($this->user->id);
        }

        return $this->success(['user' => $this->user->getProfile()]);
    }
}
