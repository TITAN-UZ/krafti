<?php

use GO\Job;

$base = dirname(__DIR__, 2) . '/';
$dir = __DIR__ . '/';
require $base . 'core/vendor/autoload.php';

$scheduler = new GO\Scheduler();

$scheduler->raw('git pull --quiet', [], 'git_pull')
    ->everyMinute(10)
    ->inForeground()
    ->onlyOne();

$scheduler->call(
    static function () {
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

$scheduler->php($dir . 'vimeo.php', null, [], 'vimeo')
    ->hourly(2)
    ->inForeground()
    ->onlyOne();

$scheduler->php($dir . 'diplomas.php', null, [], 'diplomas')
    ->hourly(20)
    ->inForeground()
    ->onlyOne();

$scheduler->php($dir . 'messages.php', null, [], 'messages')
    ->daily(1)
    ->inForeground()
    ->onlyOne();

$executed = $scheduler->run();

/** @var Job $job */
foreach ($executed as $job) {
    if ($output = $job->getOutput()) {
        if (is_array($output)) {
            $output = implode("\n", $output);
        }
        echo $output;
    }
}