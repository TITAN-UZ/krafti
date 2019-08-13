<?php

/** @var App\Container $container */
/** @var Slim\App $app */
require '_initialize.php';

$vimeo = new App\Service\Vimeo($container);

$vimeo->import();