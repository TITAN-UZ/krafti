<?php
/** @var Slim\App $app */
require '_initialize.php';

use App\Model\File;
use App\Model\User;
use App\Model\UserOauth;
use Intervention\Image\ImageManager;

$manager = new ImageManager(['driver' => 'imagick']);

/** @var User $user */
foreach (User::query()->whereNull('photo_id')->get() as $user) {
    /** @var UserOauth $oauth */
    foreach ($user->oauths()->get() as $oauth) {
        if ($oauth->photoURL && $image = file_get_contents($oauth->photoURL)) {
            $res = $manager->make($image);
            if ($data = $res->encode('data-url')) {
                $file = new File();
                if ($file->uploadFile($data)) {
                    $user->photo_id = $file->id;
                    $user->save();
                }
            }
        }
    }
}