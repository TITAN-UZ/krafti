<?php

use App\Model\Diploma;
use App\Model\File;

/** @var App\Container $container */
/** @var Slim\App $app */
require '_initialize.php';
$font = BASE_DIR . '/core/templates/fonts/koskobold.ttf';
$new = 0;

foreach (Diploma::query()->whereNull('file_id')->get() as $diploma) {
    /** @var Diploma $diploma */
    $course = $diploma->course;
    $file = $course->diploma->getFile();
    $template = $course->diploma->type == 'image/jpeg'
        ? imagecreatefromjpeg($file)
        : imagecreatefrompng($file);
    $color = imagecolorallocate($template, 0xFF, 0x74, 0x74); // Pink
    if ($child = $diploma->child) {
        imagefttext($template, 50, 0, 160, 750, $color, $font, $child->name);
    } else {
        imagefttext($template, 50, 0, 160, 750, $color, $font, $diploma->user->fullname);
    }
    $tmp_name = tempnam(BASE_DIR . '/tmp/', 'diploma_');
    $data = imagejpeg($template, $tmp_name);
    imagedestroy($template);

    $file = new File();
    if ($id = $file->uploadFile(['tmp_name' => $tmp_name, 'type' => 'image/jpeg', 'name' => 'Diploma'])) {
        $diploma->file_id = $id;
        $diploma->save();
        $new++;

        if ($child = $diploma->child) {
            $diploma->user->sendMessage('Мы сгенерировали диплом об окончании курса "' . $course->title . ' ' . ($child->gender ? 'вашей дочери' : 'вашему сыну') . ' ' . $child->name, 'diploma');
        } else {
            $diploma->user->sendMessage('Мы сгенерировали вам диплом об окончании курса "' . $course->title, 'diploma');
        }
    }
    @unlink($tmp_name);
}

$container->logger->info('Processed all diplomas', [
    'new' => $new,
]);