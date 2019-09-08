<?php

namespace App\Processors;

use App\Model\Course;
use App\Model\User;

class Test extends \App\GetProcessor
{

    public function get()
    {
        $course_id = 1;
        $user_id = 2;
        $font = BASE_DIR . '/core/templates/fonts/koskobold.ttf';
        /** @var Course $course */
        if ($course = Course::query()->find($course_id)) {
            if ($course->diploma) {
                $file = $course->diploma->getFile();
                $template = $course->diploma->type == 'image/jpeg'
                    ? imagecreatefromjpeg($file)
                    : imagecreatefrompng($file);
                $black = imagecolorallocate($template, 0xFF, 0x74, 0x74);
                //ff7474
                /** @var User $user */
                if ($user = User::query()->find($user_id)) {
                    foreach ($user->children as $child) {
                        imagefttext($template, 50, 0, 160, 750, $black, $font, $child['name']);

                        header('Content-Type: ' . $course->diploma->type);
                        header('Content-Length: ' . strlen($template));

                        imagejpeg($template);
                        imagedestroy($template);
                        exit;
                    }
                }
            }
        }
    }

}