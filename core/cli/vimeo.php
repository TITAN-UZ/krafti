<?php

use App\Model\Lesson;

/** @var Slim\App $app */
require '_initialize.php';

$vimeo = new App\Service\Vimeo();
$vimeo->import();

/** @var Lesson $lesson */
foreach (Lesson::query()->get() as $lesson) {
    $lesson->updateViewsCount();
}