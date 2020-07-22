<?php

use App\Model\Diploma;
use App\Model\File;
use App\Model\UserProgress;
use App\Service\Logger;
use Intervention\Image\AbstractFont;
use Intervention\Image\ImageManager;

require '_initialize.php';

$manager = new ImageManager(['driver' => 'imagick']);
$ttf = getenv('BASE_DIR') . '/core/templates/fonts/koskobold.ttf';
$color = '#ff7474';
$new = 0;

$progresses = UserProgress::query()->where(['section' => 0, 'rank' => 0]);
/** @var UserProgress $progress */
foreach ($progresses->get() as $progress) {
    $key = ['course_id' => $progress->course_id, 'user_id' => $progress->user_id];
    if (!$diploma = Diploma::query()->where($key)->first()) {
        $diploma = new Diploma($key);
        $diploma->child_id = null;
        $diploma->save();
    }
    if ($progress->user->children) {
        foreach ($progress->user->children as $child) {
            $key['child_id'] = $child->id;
            if (!$diploma = Diploma::query()->where($key)->first()) {
                $diploma = new Diploma($key);
                $diploma->save();
            }
        }
    }
}

/** @var Diploma $diploma */
foreach (Diploma::query()->whereNull('file_id')->get() as $diploma) {
    $course = $diploma->course;
    if (!$course->diploma_id) {
        continue;
    }
    $image = $manager->make($course->diploma->getFile());

    if ($child = $diploma->child) {
        $image->text($child->name, 160, 750, static function (AbstractFont $font) use ($ttf, $color) {
            $font->file($ttf);
            $font->size(50);
            $font->color($color);
        });
    } else {
        $image->text($diploma->user->fullname, 160, 750, static function (AbstractFont $font) use ($ttf, $color) {
            $font->file($ttf);
            $font->size(50);
            $font->color($color);
        });
    }
    $date = $diploma->created_at->format('d.m.Y');
    $image->text($date, 1000, 750, static function (AbstractFont $font) use ($ttf, $color) {
        $font->file($ttf);
        $font->size(40);
        $font->color($color);
    });
    $data = $image->encode('data-url')->__toString();

    $file = new File();
    if ($file->uploadFile($data, ['name' => 'Diploma'])) {
        $diploma->file_id = $file->id;
        $diploma->save();
        $new++;

        if ($child = $diploma->child) {
            $diploma->user->sendMessage(
                'Мы сгенерировали диплом об окончании курса "' . $course->title . ' ' .
                ($child->gender ? 'вашей дочери' : 'вашему сыну') . ' ' . $child->name, 'diploma'
            );
        } else {
            $diploma->user->sendMessage('Мы сгенерировали вам диплом об окончании курса "' . $course->title, 'diploma');
        }
    }
}

(new Logger())->info('Processed all diplomas', ['new' => $new]);