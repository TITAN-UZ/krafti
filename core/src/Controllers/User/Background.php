<?php

namespace App\Controllers\User;

use App\Model\File;
use App\Model\User;
use Vesp\Controllers\Controller;

class Background extends Controller
{
    protected $scope = 'profile';

    /** @var User $user */
    protected $user;

    public function post()
    {
        if (!$file = $this->user->background) {
            $file = new File();
        }

        $uploaded = $this->request->getUploadedFiles();
        if ($file->uploadFile(array_pop($uploaded), json_decode($_POST['metadata'], true))) {
            $this->user->background_id = $file->id;
            $this->user->save();
        }

        return $this->success(['user' => $this->user->getProfile()]);
    }
}
