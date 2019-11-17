<?php
/** @var App\Container $container */
/** @var Slim\App $app */
require '_initialize.php';

use App\Model\Homework;
use App\Model\User;
use App\Model\UserProgress;

/** @var User $user */
foreach (User::query()->get() as $user) {

    /** @var UserProgress $progress */
    if ($progress = $user->progresses()->where('course_id', '=', 1)->first()) {
        if (!$progress->section && !$progress->rank) {
            continue;
        }

        /** @var Homework $homework */
        $homework = $user->homeworks()
            ->where('section', '>=', $progress->section)
            ->orderBy('section', 'desc')
            ->orderBy('lesson_id', 'desc')
            ->first();

        if ($homework) {
            echo "$user->id: $user->fullname: $homework->section, $progress->section/$progress->rank";
            if ($homework->section >= 3) {
                $progress->section = 0;
                $progress->rank = 0;
            } else {
                $progress->section = ($homework->section + 1);
                $progress->rank = 0;
            }
        } else {
            continue;
        }

        /** @var Homework $homework */
        $homework = $user->homeworks()
            ->where('section', '=', 0)
            ->orderBy('lesson_id', 'desc')
            ->first();
        if ($homework) {
            if ($homework->lesson->section == $progress->section && $homework->lesson->rank > $progress->rank) {
                $progress->rank = $homework->lesson->rank;
            }
        }

        echo " --- $progress->section/$progress->rank\n";
        //$progress->save();
    }
}

