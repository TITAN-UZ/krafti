<?php

exit('Disabled!');

/** @var App\Container $container */
/** @var Slim\App $app */
require '_initialize.php';

/** @var Homework $work */
foreach (\App\Model\Homework::query()->get() as $work) {
    $work->delete();
}