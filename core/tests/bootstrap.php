<?php

use Vesp\Helpers\Env;

require_once dirname(__DIR__) . '/vendor/autoload.php';

Env::loadFile(dirname(__DIR__) . '/.test.env');
