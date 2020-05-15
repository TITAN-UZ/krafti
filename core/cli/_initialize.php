<?php

use Vesp\Helpers\Env;
use Vesp\Services\Eloquent;

$base = dirname(dirname(dirname(__FILE__))) . '/';
require $base . 'core/vendor/autoload.php';

Env::loadFile($base . '/core/' . (get_current_user() == 's4000' ? '.prod' : '.dev') . '.env');
new Eloquent();