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

        if (!$uploaded = $this->request->getUploadedFiles()) {
            return $this->failure('Не могу загрузить файл');
        }
        $uploaded = array_pop($uploaded);

        $metadata = $this->getProperty('metadata');
        if (!empty($metadata) && $file->uploadFile($uploaded, json_decode($metadata, true))) {
            $this->user->photo_id = $file->id;
            $this->user->save();

            $this->user = User::query()->find($this->user->id);
        }

        return $this->success(['user' => $this->user->getProfile()]);
    }
}
