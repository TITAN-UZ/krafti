<?php

use \App\Model\User;
use \App\Model\UserChild;
use \App\Model\Order;

/** @var App\Container $container */
/** @var Slim\App $app */
require '_initialize.php';

$warning = getenv('ORDER_LIMIT_DAYS');

foreach (UserChild::query()->where('dob', 'LIKE', '%-' . date('m-d'))->get() as $child) {
    /** @var UserChild $child */
    $child->user->sendMessage('Поздравляем с днём рождения ' . ($child->gender ? 'вашу дочь' : 'вашего сына') . ' ' . $child->name, 'dob');
}

foreach (User::query()->where('dob', 'LIKE', '%-' . date('m-d'))->get() as $user) {
    /** @var User $user */
    $user->sendMessage('Поздравляем вас с днём рождения!', 'dob');
}

$date = date('Y-m-d', time() + ($warning * 86400));
foreach (Order::query()->where('paid_till', 'LIKE', $date . '%')->get() as $order) {
    /** @var Order $order */
    $order->user->sendMessage('Через ' . $warning . ' дней у вас заканчивается срок оплаты курса "' . $order->course->title . '"', 'warning', null, [
        'course_id' => $order->course_id,
    ]);
}