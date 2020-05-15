<?php

namespace App\Service;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\EchoHandler;
use Monolog\Handler\RotatingFileHandler;

class Logger extends \Monolog\Logger
{
    public function __construct()
    {
        parent::__construct('logger');

        if (PHP_SAPI == 'cli') {
            $handler = new EchoHandler(constant('\Monolog\Logger::' . getenv('LOG_LEVEL_CLI')));
            $handler->setFormatter(new LineFormatter(null, null, false, true));
        } else {
            $handler = new RotatingFileHandler(
                getenv('LOG_DIR') . 'core.log',
                5,
                constant('\Monolog\Logger::' . getenv('LOG_LEVEL_WWW'))
            );
        }
        $this->pushHandler($handler);
    }
}
