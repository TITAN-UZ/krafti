<?php

use Vesp\Helpers\Env;
use Vesp\Services\Eloquent;

require dirname(__FILE__) . '/vendor/autoload.php';
Env::loadFile(dirname(__DIR__) . '/core/' . (get_current_user() == 'promo' ? '.prod' : '.dev') . '.env');

$eloquent = new Eloquent();
$config = $eloquent->getConnection()->getConfig();

return [
    'paths' => [
        'migrations' => getenv('BASE_DIR') . 'core/db/migrations',
        'seeds' => getenv('BASE_DIR') . 'core/db/seeds',
    ],
    'migration_base_class' => '\Vesp\Services\Migration',
    'environments' => [
        'default_migration_table' => $config['prefix'] . 'migrations',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => $config['driver'],
            'host' => $config['host'],
            'name' => $config['database'],
            'user' => $config['username'],
            'pass' => $config['password'],
            'port' => $config['port'],
            'charset' => $config['charset'],
            'collation' => $config['collation'],
            'table_prefix' => $config['prefix'],
        ],
    ],
];
