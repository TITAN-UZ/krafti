<?php
/** @var App\Container $container */
/** @var Slim\App $app */
require '_initialize.php';

use App\Model\Homework;
use App\Model\User;
use App\Model\UserProgress;

/** @var User $user */
foreach (User::query()->whereHas('orders')->get() as $user) {

    /** @var UserProgress $progress */
    if ($progress = $user->progresses()->where('course_id', '=', 1)->first()) {
        if (!$progress->section && !$progress->rank) {
            continue;
        }

        /** @var Homework $section */
        $section = $user->homeworks()
            ->orderBy('section', 'desc')
            ->orderBy('lesson_id', 'desc')
            ->first();

        if ($section) {
            if ($section->section > $progress->section) {
                echo $user->id, ' ' . $user->fullname, ': ', $progress->section, ' - ', $section->section, "\n";
                $progress->section = $section->section;
                /** @var Homework $lesson */
                $lesson = $user->homeworks()
                    ->join('lessons', 'lessons.id', '=', 'homeworks.lesson_id')
                    ->orderBy('lessons.section', 'desc')
                    ->orderBy('lessons.rank', 'desc')
                    ->first();
                if ($lesson) {
                    print_r($lesson->toArray());die;
                } else {
                    $progress->rank = 1;
                }
                //$progress->save();
            }
        }
    }
}

