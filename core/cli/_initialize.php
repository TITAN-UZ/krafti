<?php

use Vesp\Helpers\Env;
use Vesp\Services\Eloquent;

$base = dirname(__FILE__, 3) . '/';
require $base . 'core/vendor/autoload.php';

Env::loadFile($base . '/core/.env');
new Eloquent();