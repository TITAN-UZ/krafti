<?php

require dirname(__DIR__) . '/core/vendor/autoload.php';

$container = new \App\Container();
$app = new \Slim\App($container);
//$app->add($container->jwt);
$app->add(new RKA\Middleware\IpAddress());

$app->get('/docs/v1', function($request, $response, $args) {
    $openapi = \OpenApi\scan([
        BASE_DIR . '/core/src/Controllers/',
        BASE_DIR . '/core/src/Processors/',
    ]);
    header('Content-Type: application/json');
    //header('Content-Type: application/x-yaml');
    echo $openapi->toJson();
});

$app->any('/api[/{name:.+}]', function ($request, $response, $args) use ($container) {
    $container->request = $request;
    $container->response = $response;
    return (new \App\Controllers\Api($container))->process($request, $response, $args);
});

try {
    $app->run();
} catch (Throwable $e) {
    exit($e->getMessage());
}