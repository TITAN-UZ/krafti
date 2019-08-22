<?php

use App\Model\Lesson;

/** @var App\Container $container */
/** @var Slim\App $app */
require '_initialize.php';

$vimeo = new App\Service\Vimeo($container);
$vimeo->import();

/** @var Lesson $lesson */
foreach (Lesson::query()->get() as $lesson) {
    $lesson->updateViewsCount();
}