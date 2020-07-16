<?php

use Vesp\Helpers\Env;
use Vesp\Services\Eloquent;
use Vesp\Services\Migration;

define('BASE_DIR', dirname(__DIR__) . '/');
require BASE_DIR . 'core/vendor/autoload.php';
Env::loadFile(BASE_DIR . 'core/.env');

return [
    'paths' => [
        'migrations' => BASE_DIR . 'core/db/migrations',
        'seeds' => BASE_DIR . 'core/db/seeds',
    ],
    'migration_base_class' => Migration::class,
    'environments' => [
        'default_migration_table' => getenv('DB_PREFIX') . 'migrations',
        'default_environment' => 'dev',
        'dev' => [
            'name' => getenv('DB_DATABASE'),
            'connection' => (new Eloquent())->getConnection()->getPdo(),
        ],
    ],
];