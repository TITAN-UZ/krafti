<?php

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Vesp\Helpers\Env;

require dirname(__DIR__) . '/core/vendor/autoload.php';
Env::loadFile(dirname(__DIR__) . '/core/' . (get_current_user() == 's4000' ? '.prod' : '.dev') . '.env');

$app = DI\Bridge\Slim\Bridge::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(App\Middlewares\Auth::class);
$app->add(new RKA\Middleware\IpAddress());

$app->any('/api[/{name:.+}]', [App\Controllers\Api::class, 'process']);
$app->get('/image/{id:\d+}[/{size:\d+x\d+}[/{params}]]', [App\Controllers\Image::class, 'process']);
$app->get('/file/{id:\d+}[/{params}]', [App\Controllers\File::class, 'process']);

if (getenv('ENV') === 'dev') {
    $app->get('/upload[/{file:.+}]', function (Request $request, ResponseInterface $response, array $args) {
        return $response->withStatus(302)->withHeader('Location', 'https://krafti.ru/upload/' . $args['file']);
    });
}

$app->get('/', function (Request $request, ResponseInterface $response) {
    $html = file_get_contents(getenv('BASE_DIR') . '/frontend/dist/200.html');
    $body = $response->getBody();
    $body->write($html);

    return $response->withBody($body);
});

try {
    $app->run();
} catch (Throwable $e) {
    exit($e->getMessage());
}