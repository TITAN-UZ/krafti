<?php

require dirname(__DIR__) . '/core/vendor/autoload.php';

$container = new \App\Container();
$app = new \Slim\App($container);
$app->add($container->jwt);

$app->any('/api[/{name:.+}]', function ($request, $response, $args) use ($container) {
    return (new \App\Controllers\Api($container))->process($request, $response, $args);
});

try {
    $app->run();
} catch (Exception $e) {
    exit($e->getMessage());
}