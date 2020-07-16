<?php

namespace App\Controllers\User;

use App\Model\File;
use App\Model\User;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Background extends Controller
{
    protected $scope = 'profile';
    /** @var User $user */
    protected $user;

    public function post(): ResponseInterface
    {
        if (!$file = $this->user->background) {
            $file = new File();
        }

        if (!$uploaded = $this->request->getUploadedFiles()) {
            return $this->failure('Не могу загрузить файл');
        }
        $uploaded = array_pop($uploaded);

        $metadata = $this->getProperty('metadata');
        if (!empty($metadata) && $file->uploadFile($uploaded, json_decode($metadata, true))) {
            $this->user->background_id = $file->id;
            $this->user->save();
        }

        return $this->success(['user' => $this->user->fresh()->getProfile()]);
    }
}
