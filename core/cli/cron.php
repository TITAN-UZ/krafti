<?php

$base = dirname(dirname(__DIR__)) . '/';
require $base . 'core/vendor/autoload.php';

$scheduler = new GO\Scheduler();
$scheduler
    ->call(function () {
        system('~/bin/pm2 ping > /dev/null 2>&1');
        system('~/bin/pm2 status | grep `whoami` > /dev/null 2>&1', $code);
        if ($code) {
            system('rm ~/tmp/nuxt.sock > /dev/null 2>&1');
            system('cd ~/frontend && ~/bin/yarn start > /dev/null 2>&1', $code);
            if (!$code) {
                echo 'PM2 has started!';
            }
        }
    }, [], 'pm2')
    ->everyMinute(5)
    ->onlyOne()
    ->output('php://stdout');

$scheduler->run();