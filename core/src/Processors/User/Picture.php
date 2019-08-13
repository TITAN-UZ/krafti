<?php

namespace App\Processors\User;

use App\Model\File;
use App\Model\User;

class Picture extends \App\Processor
{

    protected $scope = 'profile';


    public function post()
    {
        if (!$file = $this->container->user->photo) {
            $file = new File();
        }

        if ($id = $file->uploadFile($_FILES['file'], json_decode($_POST['metadata'], true))) {
            $this->container->user->photo_id = $id;
            $this->container->user->save();

            $this->container->user = User::query()->find($this->container->user->id);
        }

        return (new Profile($this->container))->get();
    }

}