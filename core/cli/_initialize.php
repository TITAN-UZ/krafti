<?php

$base = dirname(dirname(dirname(__FILE__))) . '/';
require $base . 'core/vendor/autoload.php';

$container = new App\Container();
$app = new Slim\App($container);